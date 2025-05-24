<?php
    $dir = readline("Проверяем [A/B/C/D]: ");   # Запрашиваем у пользователя название подкаталога
    $path = __DIR__ . "/" . $dir;               # Формируем абсолютный путь к подкаталогу
    $php_script = $path . "/" . $dir . ".php";  # Путь к PHP-скрипту, который нужно проверить

    # Перебираем все файлы с расширением .dat в выбранной директории
    foreach (glob("$path/*.dat") as $dat_file) {
        $ans_file = str_replace(".dat", ".ans", $dat_file);     # Для каждого .dat файла находим соответствующий .ans файл

        $output = shell_exec("php \"$php_script\" < \"$dat_file\"");    # Выполняем PHP-скрипт с входными данными из .dat файла
        $result = file_get_contents($ans_file);                         # Читаем ожидаемый результат из .ans файла

        # Удаляем лишние пробелы в конце строк
        $output = preg_replace('/\s+$/m', '', $output);
        $result = preg_replace('/\s+$/m', '', $result);

        # Сравниваем фактический вывод скрипта и ожидаемый результат
        if ($output == $result) {
            echo basename($dat_file) . " OK\n";
        } else {
            echo basename($dat_file) . " FAIL\n";
        }
    }
?>