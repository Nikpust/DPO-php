<?php

    namespace App\core;

    use PDO;
    use PDOException;

    class Database {
        
        private static ?PDO $pdo = null;

        public static function connect(): PDO {
            if (self::$pdo === null) {
                $host = getenv('DB_HOST');
                $port = getenv('DB_PORT');
                $dbname = getenv('DB_NAME');
                $user = getenv('DB_USER');
                $pass = getenv('DB_PASSWORD');

                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

                try {
                    self::$pdo = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                } catch (PDOException $e) {
                    die("Ошибка подключения к базе данных: " . $e->getMessage());
                }
            }
            
            return self::$pdo;
        }
        
    }

?>