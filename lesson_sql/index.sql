@alexxhub



/* Создание таблицы */
CREATE TABLE IF NOT EXISTS users (
    `ID` INT(100) NOT NULL AUTOINCREMENT,
    `LOGIN` CHAR(255) NOT NULL,
    `PASSWORD` VARCHAR(65535) NOT NULL,
    `BDATE` DATE,
    `DATE_REGISTRATION` DATETIME DEFAULT CURRENT_TIME 
    /* DATE, TIME, YEAR(N), TIMESTAMP, DATETIME */,
    `RATING` FLOAT,
    `GENDER` ENUM(1) DEFAULT 1 FROM TABLE gender,
    `COMMENT` TEXT, /* BLOB */
    /* TINYTEXT, TINYBLOB, MEDUIMTEXT, MEDIUMBLOB, LARG */
    PRIMARY KEY (ID)
);

/* Удалить таблицу */
DROP TABLE IF EXISTS users;

/* Создать индекс */
CREATE UNIQUE INDEX users_id ON users (ID);

/* Удалить индекс */
DROP INDEX users_id ON users;

/*
C - create
R - read
U - update
D - delete
*/

/* Добавление в таблицу базы */
INSERT INTO users (LOGIN, PASSWORD, DATE_REGISTRATION) VALUES ('admin', '1234556', CURRENT);

/* Обновление записи в таблице */
UPDATE users SET LOGIN = 'user', PASSWORD = '222222' WHERE ID = 1;

/* Удаление записи из таблицы */
DELETE FROM users WHERE ID = 10;

/* Чтение из таблицы */
SELECT * FROM users;
SELECT AGE FROM users WHERE ID = 10;
SELECT AGE, LOGIN, ID, PASSWORD FROM users;
SELECT AGE * 365 AS DAYS, AGE FROM users WHERE (ID = 20 OR ID = 30) OR LOGIN = 'admin';
SELECT * FROM users WHERE BDATE > DATE('04.08.2006');
SELECT * FROM users WHERE AGE BETWEEN(20, 40);
SELECT * FROM users, groups WHERE users.GROUP_ID = groups.ID RIGHT JOIN rules WHERE rules.ID = groups.RULE_ID;