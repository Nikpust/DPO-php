<?php
    $dir = readline("Проверяем [A/B/C]: ");     # Запрашиваем у пользователя название подкаталога
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
        if ($dir != "C") {
            if ($output == $result) {
                echo basename($dat_file) . " OK\n";
            } else {
                echo basename($dat_file) . " FAIL\n";
            }
        } else {
            # Для задачи C сравниваем результат с погрешностью 0.0013 "ошибка округления в ответах"
            $output_exp = explode("\n", $output);
            $result_exp = explode("\n", $result);

            $flag = true;

            for ($i = 0; $i < count($output_exp); $i += 1) {
                $output_exp_exp = explode(" ", $output_exp[$i]);
                $result_exp_exp = explode(" ", $result_exp[$i]);

                if (abs($output_exp_exp[1] - $result_exp_exp[1]) > 0.0013) {
                    $flag = false;
                }
            }

            if ($flag) {
                echo basename($dat_file) . " OK\n";
            } else {
                echo basename($dat_file) . " FAIL\n";
            }
        }
    }
?>