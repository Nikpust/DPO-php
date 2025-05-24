<?php
    $dir = readline("Проверяем [A/B/C]: ");     # Запрашиваем у пользователя название подкаталога
    $path = __DIR__ . "/" . $dir;               # Формируем абсолютный путь к подкаталогу
    $php_script = $path . "/" . $dir . ".php";  # Путь к PHP-скрипту, который нужно проверить
    
    # Для "A" работаем с файлами формата .dat, для "B" с файлами формата 00X.xml
    if ($dir == "A") {
        # Перебираем все файлы с расширением .dat в выбранной директории
        foreach (glob("$path/*.dat") as $dat_file) {
            $ans_file = str_replace(".dat", ".ans", $dat_file);     # Для каждого .dat файла находим соответствующий .ans файл
    
            $output = shell_exec("php \"$php_script\" < \"$dat_file\"");    # Выполняем PHP-скрипт с входными данными из .dat файла
            $result = file_get_contents($ans_file);                         # Читаем ожидаемый результат из .ans файла

            # Удаляем лишние пробелы в конце строк
            $output = preg_replace('/\s+$/m', '', $output);
            $result = preg_replace('/\s+$/m', '', $result);
    
            # Сравниваем фактический вывод скрипта и ожидаемый результат
            if ($output === $result) {
                echo basename($dat_file) . " OK\n";
            } else {
                echo basename($dat_file) . " FAIL\n";
            }
        }
    } else {
        # Для "B" перебираем файлы формата 00X.xml
        for ($i = 001; $i < 007; $i+=001) {
            $file = "00" . $i;  # Формируем имя файла 00X.xml

            # Выполняем скрипт и считываем разультат
            $output = shell_exec("php \"$php_script\" $file");
            $result = file_get_contents(__DIR__ . "\B" . "\\" . $file  . "_result.xml");
            
            # Удаляем лишние пробелы в конце строк
            $output = preg_replace('/\s+$/m', '', $output);
            $result = preg_replace('/\s+$/m', '', $result);

            if ($output === $result) {
                echo $file . ".xml: OK\n";
            } else {
                echo $file . ".xml: FAIL\n";
            }
        }
    }
?>