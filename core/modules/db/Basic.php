<?php

namespace Core\DB;

use Core\Main\Settings;
use PDO;
use PDOException;

class Basic
{
    private $dbname;
    private $database;
    private $host;
    private $user;
    protected $password;
    private $conn;

    public function __construct(string $dbname = 'default')
    {
        $this->dbname = $dbname;
        $arSettings = Settings::getDbParams($dbname);
        $this->host = $arSettings['host'];
        $this->user = $arSettings['user'];
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
            \Core\Main\Logs::add2Log('Connection fails: ' . $e->getMessage());
            $this->conn = null;
            return false;
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
        $select = join(', ', $params['select']) ?? '*';

        $sql .= $select . ' ';
        $sql .= 'FROM ' . $table;

        //Применение фильтров в выборке
        if (is_array($params['filter']) && !empty($params['filter'])) {
            foreach ($params['filter'] as $key => $value) {
                $filter[] = $key . ' = ?';
                $execute[] = $value;
            }
        }

        if (!empty($filter)) {
            $sql .= ' WHERE ' . join(', ', $filter);
        }

        //Применение лимитов и стратовой позиции выборки
        if (!empty($params['limit'])) {
            $limit = $params['limit']['rows'] > 0 ? $params['limit']['rows'] : 100;
            $offset = $params['limit']['offset'] > 0 ? $params['limit']['offset'] : 0;

            $sql .= ' LIMIT = ' . $limit;
            $sql .= ' OFFSET = ' . $offset;
        }

        //Сортировка
        if (!empty($params['order'])) {
            $key = array_key_first($params['order']);
            $sql .= ' ORDER BY ' . $key  . ' = ' . $params['order'][$key];
        }

        //Запрос и ответ
        $result = [];
        try {
            $request = $this->conn->prepare($sql);
            $request->execute($execute);

            $response = $request->fetchAll(PDO::FETCH_ASSOC);

            //Обработка ответа
            foreach ($response as $row) {
                $result[] = $row;
            }
        } catch (PDOException $e) {
            \Core\Main\Logs::add2Log('Query get list fail: ' . $e->getMessage()); 
        }
        return $result;
    }
}
