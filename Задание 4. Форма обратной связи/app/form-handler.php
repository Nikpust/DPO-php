<?php
    date_default_timezone_set("Europe/Moscow");     # Устанавливаем часовой пояс по умолчанию
    
    require "db.php";   # Подключаемся к базе данных

    # Функция для валидации данных по регулярному выражению
    function validation($data, $pattern) {
        return preg_match($pattern, $data);
    }

    # Функция для отправки письма через SMTP-сервер
    function send_mail($to, $subject, $message) : void {
        $smtp_server = getenv("SMTP_SERVER");
        $smtp_port = getenv("SMTP_PORT");
        $smtp_user = getenv("SMTP_USER");
        $smtp_pass = getenv("SMTP_PASS");
        
        # При помощи заголовка сервер распознает от кого письмо, кому отправлять ответ, какой формат письма
        $headers = "From: " . $smtp_user . "\r\n";
        $headers .= "Reply-To: " . $smtp_user . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
        # Открываем соединение с SMTP-сервером по SSL
        $socket = fsockopen("ssl://$smtp_server", $smtp_port, $errno, $errstr, 30);
        
        # Устанавливаем связь с сервером
        fputs($socket, "EHLO $smtp_server\r\n");
        fgets($socket, 512);
    
        # Отправляем запрос на логин
        fputs($socket, "AUTH LOGIN\r\n");
        fgets($socket, 512);

        # Логинимся
        fputs($socket, base64_encode($smtp_user) . "\r\n");
        fgets($socket, 512);
    
        fputs($socket, base64_encode($smtp_pass) . "\r\n");
        fgets($socket, 512);
    
        # От кого письмо
        fputs($socket, "MAIL FROM: <$smtp_user>\r\n");
        fgets($socket, 512);
    
        # Кому письмо
        fputs($socket, "RCPT TO: <$to>\r\n");
        fgets($socket, 512);

        # Сообщаем серверу, что готовы начать отправку письма
        fputs($socket, "DATA\r\n");
        fgets($socket, 512);
    
        fputs($socket, "Subject: $subject\r\n");
        fputs($socket, "$headers\r\n");
        fputs($socket, "$message\r\n");
        fputs($socket, ".\r\n"); # Завершение письма для SMPT-сервера
        fgets($socket, 512);

        # Закрываем соединение
        fputs($socket, "QUIT\r\n");
        fclose($socket);
    }

    # Проверяем, что запрос был отправлен методом POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        # Получаем и очищаем введенные пользователем данные
        $full_name = trim($_POST["full-name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $comment = trim($_POST["comment"] ?? "");

        # Шаблоны для проверки корректности ввода данных
        $patterns = [
            'full_name' => '/^[a-zA-Zа-яА-ЯёЁ]+(?:\s[a-zA-Zа-яА-ЯёЁ]+)+$/u',
            'email' => '/^[A-Za-z0-9][A-Za-z0-9_.-]{2,29}@[A-Za-z]{2,30}\.[a-z]{2,10}$/',
            'phone' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}-[0-9]{2}-[0-9]{2}$/'
        ];

        # Хранение ошибок
        $message = "";

        # Проверяем корректность введенных данных
        if (!validation($full_name, $patterns['full_name'])) {
            $message .= " ФИО,";
        }
        if (!validation($email, $patterns['email'])) {
            $message .= " E-mail,";
        }
        if (!validation($phone, $patterns['phone'])) {
            $message .= " номер телефона,";
        }

        $message = rtrim($message, ',');

        # Если ошибок нет, то продолжаем обработку запроса
        if ($message == "") {

            try {
                # Проверяем, есть ли уже запросы от этого email в базе данных
                $sql = "SELECT request_created_at FROM requests WHERE request_email = :email ORDER BY request_created_at DESC LIMIT 1";
                $sth = $database->prepare($sql);
                $sth->execute([":email" => $email]);
                $last_request = $sth->fetch(PDO::FETCH_ASSOC);

                # Если запрос был отправлен менее часа назад, то выводим сообщение об ошибке
                if ($last_request) {
                    # Вычисляем разницу во времени между последним запросом и текущим временем
                    $last_time = strtotime($last_request['request_created_at']);
                    $current_time = time();
                    $time_diff = $current_time - $last_time;
    
                    # Если разница меньше 3600 секунд (1 час), то выводим сообщение об ошибке
                    if ($time_diff < 3600) {
                        $seconds_remaining = 3600 - $time_diff;
                        $minutes_remaining = floor($seconds_remaining / 60);
                        $seconds = $seconds_remaining % 60;
                        echo json_encode([
                            "success" => false,
                            "message" => "Вы уже отправили запрос. Повторная отправка будет доступна через $minutes_remaining минут $seconds секунд!"
                        ]);
                        exit;
                    }
                }

                # Если запросов не было или прошло больше часа, то сохраняем новый запрос в БД
                $sql = "INSERT INTO requests (request_created_at, request_full_name, request_email, request_phone_number, request_comment) 
                        VALUES (:time, :full_name, :email, :phone, :comment)";
                $time = date("H:i:s, d M Y");
                $sth = $database->prepare($sql);
                $sth->execute([
                    ":time" => $time,
                    ":full_name" => $full_name,
                    ":email" => $email,
                    ":phone" => $phone,
                    ":comment" => $comment
                ]);

                # Если запрос успешно сохранен, то отправляем письмо и выводим ответ
                $full_name_array = explode(" ", $full_name);

                $to = "20_nik_05@mail.ru";
                $subject = "Обратная связь с сайта";
                $message = "
                    <h2>Новое сообщение</h2>
                    <p><strong>ФИО:</strong> " . $_POST['full-name'] . "</p>
                    <p><strong>Email:</strong> " . $_POST['email'] . "</p>
                    <p><strong>Телефон:</strong> " . $_POST['phone'] . "</p>
                    <p><strong>Комментарий:</strong> " . htmlspecialchars($_POST['comment']) . "</p>
                ";

                send_mail($to, $subject, $message);

                echo json_encode([
                    "success" => true,
                    "time" => date("Y-m-d H:i:s", strtotime($time . " +90 minutes")),
                    "name" => $full_name_array[1],
                    "surname" => $full_name_array[0],
                    "patronymic" => $full_name_array[2] ?? "",
                    "email" => $email,
                    "phone" => $phone
                ]);
                exit;
            } catch (PDOException $e) {
                echo json_encode(["success" => false, "message" => "Ошибка базы данных" . $e->getMessage()]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "Неправильно заполнены поля:" . $message]);
            exit;
        }
    }
?>