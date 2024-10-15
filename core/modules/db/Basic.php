<?php

namespace DB;

use Main\Settings;
use Main\Logs;
use PDO;
use PDOException;

class Basic
{
    public $dbname;
    public $database;
    public $host;
    public $user;
    public $password;
    public $conn;

    public $settings;

    public function __construct(string $dbname = 'default')
    {
        $this->settings = new Settings();
        $arSettings = $this->settings->getDbParams($dbname);
        $this->dbname = $dbname;

        $this->host = $arSettings['host'];
        $this->user = $arSettings['login'];
        $this->password = $arSettings['password'];
        $this->database = $arSettings['database'];

        $this->connect();
    }

    private function connect(): bool
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->database",
                $this->user,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            Logs::add2Log('Connection fails: ' . $e->getMessage());
            $this->conn = null;
            return false;
        }
    }

    private function prepareFilter($arFilter, &$sql, &$filter, &$execute) {
        if (!empty($arFilter)) {
            foreach ($arFilter as $key => $value) {
                $filter[] = $key . ' = ?';
                $execute[] = $value;
            }
        }

        if (!empty($filter)) {
            $sql .= ' WHERE ' . join(', ', $filter);
        }
    }

    /**
     * <p>Получение записей из БД</p>
     * @param string $table
     * @param array $params = [
     *  'select' => ['*', 'NAME', 'PASSWORD', 'CODE'], // поля которые выбираем
     *  'order' => [ 'SORT' => 'ASC' ], // принцип сортировки
     *  'filter' => [
     *      'GROUP' => 5,
     *      'AGE' => 10
     *  ], // фильтр
     *  'limit' => [
     *      'offset' => 1 // текущая позиция выборки, умножаем на лимит при постраничной навигации
     *      'rows' => 5 // количество отбираемых строк
     *  ]   // ограничения на количество выбираемых элементов
     * ]
     * 
     * 
     * @return array
     */
    public function getList(string $table, array $params = []): array
    {
        if (!$this->conn)
            return [];

        //Значения по умолчанию
        $filter = []; //готовый фильтр
        $execute = []; //параметры фильтра
        $limit = 100;
        $offset = 0;

        //Основная выборка из таблицы
        $sql = 'SELECT ';
        $select = (!empty($params['select'])) ? join(', ', $params['select']) : '*';

        $sql .= $select . ' ';
        $sql .= 'FROM ' . $table;

        //Применение фильтров в выборке
        $this->prepareFilter($params['filter'], $sql, $filter, $execute);
        
        //Сортировка
        if (!empty($params['order'])) {
            $key = array_key_first($params['order']);
            $sql .= ' ORDER BY ' . $key  . ' ' . $params['order'][$key];
        }

        //Применение лимитов и стратовой позиции выборки
        if (!empty($params['limit'])) {
            $limit = (!empty($params['limit']['rows'])) ? $params['limit']['rows'] : $limit;
            $offset = (!empty($params['limit']['offset'])) ? $params['limit']['offset'] : $offset;

            $sql .= ' LIMIT ' . $limit;
            $sql .= ' OFFSET ' . $offset;
        }

        //Запрос и ответ
        $result = [];

        echo $sql;

        try {
            $request = $this->conn->prepare($sql);
            $request->execute($execute);

            $response = $request->fetchAll(PDO::FETCH_ASSOC);

            //Обработка ответа
            foreach ($response as $row) {
                $result[] = $row;
            }
        } catch (PDOException $e) {
            Logs::add2Log('Query get list fail: ' . $e->getMessage()); 
        }
        return $result;
    }

    /**
     * Summary of add
     * @param string $table
     * @param array $arFields = [
     *  'KEY' => 'VALUE',
     *  'KEY2' => 'VALUE'
     * ]
     * @return void
     */
    public function add(string $table, array $arFields) {
        try {
            $fields = join(', ', array_keys($arFields));
            $prepValues =  ':' .join(', :', array_keys($arFields));
            $values = [];
            
            $sql = 'INSERT INTO ' . $table . '(' . $fields . ') VALUES (' . $prepValues . ')';
            // INSERT INTO table (KEY, KEY2) VALUES (:KEY, :KEY2)
    
            $request = $this->conn->prepare($sql);
    
            foreach($arFields as $key => $value) {
                $request->bindValue(':'. $key, $value); // :KEY , VALUE
            }
    
            $newRow = $request->execute();
            //todo: сделать возврат добавленнего ID
        }
        catch(PDOException $e) {
            Logs::add2Log('Query add: ' . $e->getMessage()); 
        }
    }

    public function deleteById(string $table, int $id) {
        try {
            $sql = 'DELETE FROM ' . $table. ' WHERE ID = ?';
            $request = $this->conn->prepare($sql);
            $request->execute([$id]);
            
        }
        catch(PDOException $e) {
            Logs::add2Log('Query drop: ' . $e->getMessage()); 
        }
    }
}
