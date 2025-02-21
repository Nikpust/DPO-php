<?php
    # Задаем реугулярное выржание для декодирования ввода
    $pattern = "/<(.*)>\s([SNPDE])(?:\s(-?\d+)\s(-?\d+))?$/";

    $output = [];

    while (true) {
        $input = readline();
        if ($input == "") {
            break;
        }
        
        # Обрабатываем считанную строку, разделяя совпадения в массив matches
        preg_match($pattern, $input, $matches);
        
        switch ($matches[2]) {
            # Проверка строки по длине
            case 'S':
                if (strlen($matches[1]) >= $matches[3] && strlen($matches[1]) <= $matches[4]) {
                    $output[] = "OK";
                } else {
                    $output[] = "FAIL";
                }
                break;
            
            # Проверка числа на принадлежность диапазону
            case 'N':
                # Заводим новое регулярное выражение, чтобы удостовериться, что это число
                $patern_int = "/^-?[0-9]+$/";
                preg_match($patern_int, $matches[1], $temp);
                # Если preg_match не нашел совпадения
                if (!isset($temp[0])) {
                    $output[] = "FAIL";
                # Сверяем диапазон
                } elseif ($temp[0] >= $matches[3] && $temp[0] <= $matches[4]) {
                    $output[] = "OK";
                } else {
                    $output[] = "FAIL";
                }
                break;
            
            # Проверка номера телефона по маске +7 (999) 999-99-99
            case 'P':
                $pattern_phone = "/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/";
                if (preg_match($pattern_phone, $matches[1])) {
                    $output[] = "OK";
                } else {
                    $output[] = "FAIL";
                }
                break;
            
            # Проверка даты по формату dd.mm.yyyy hh:mm
            case 'D':
                $pattern_date = "/^(\d{1,2})\.(\d{1,2})\.(\d{4})\s(\d{1,2}):(\d{2})$/";
                preg_match($pattern_date, $matches[1], $date);
                if (empty($date)) {
                    $output[] = "FAIL";
                } elseif (checkdate($date[2], $date[1], $date[3]) && $date[4] < 24 && $date[5] < 60) {
                    $output[] = "OK";
                } else {
                    $output[] = "FAIL";
                }
                break;
                
            # Проверка почты по шаблону: имя - 4-30 букв (первая не _); @; домен - 2-30 букв; .; домен верхнего уровня - 2-10 букв
            case 'E':
                $pattern_email = "/^[A-Za-z0-9][A-Za-z0-9_]{3,29}@[A-Za-z]{2,30}\.[a-z]{2,10}$/";
                if (preg_match($pattern_email, $matches[1])) {
                    $output[] = "OK";
                } else {
                    $output[] = "FAIL";
                }
                break;
            
            default:
                $output[] = "FAIL";
                break;
        }
    }

    foreach ($output as $out) {
        echo "$out\n";
    }
?>