<?php
    # Подключаемся к базе данных PostgreSQL (данные в .env)
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT");
    $dbname = getenv("DB_NAME");
    $user = getenv("DB_USER");
    $password = getenv("DB_PASSWORD");

    # Формируем DSN для подключения к базе данных
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    # Проверяем, существует ли файл для логирования ошибок, если нет - создаем его
    $log_file = __DIR__ . "/error.log";
    if (!file_exists($log_file)) {
        file_put_contents($log_file, "");
    }

    # Пытаемся подключиться к базе данных и записать результат в лог-файл
    try {
        $database = new PDO($dsn, $user, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $message = "[" . date("Y-m-d H:i:s") . "] Успешное подключение!\n";
        file_put_contents($log_file, $message, FILE_APPEND);
    } catch (PDOException $e) {
        $message = "[" . date("Y-m-d H:i:s") . "] Ошибка базы данных: " . $e->getMessage() . "\n";
        file_put_contents($log_file, $message, FILE_APPEND);
    }
?>