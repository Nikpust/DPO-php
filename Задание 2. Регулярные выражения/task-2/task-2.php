<?php
    $path = __DIR__;                        # Абсолютный путь к текущей директории
    $php_script = $path. "/task-2.php";     # Путь к текущему скрипту
    $num = 1;                               # Счетчик для именования выходных файлов

    # Регулярное выражение для поиска устаревших ссылок
    $patern = "/http\:\/\/asozd\.duma\.gov\.ru\/main\.nsf\/\(Spravka\)\?OpenAgent\&RN\=(\d+\-\d+)\&\d+/";
    
    # Перебираем все .txt-файлы в папке Input
    foreach (glob("$path/Input/*.txt") as $data_file) {
        $content_file = file_get_contents($data_file);                                  # Читаем содержимое файла
        $updated_content = preg_replace_callback($patern, "replace", $content_file);    # Заменяем устаревшие ссылки на новые
        
        $output_files = "$path/Output/$num.txt";    # Формируем имя выходного файла
        $num++;                                     # Увеличиваем счетчик для следующего файла
        
        file_put_contents($output_files, $updated_content);     # Записываем обновленное содержимое в выходной файл
    }

    # Callback-функция для замены устаревших ссылок на новые
    function replace($matches) {
        $link = "http://sozd.parlament.gov.ru/bill/";
        $new_link = $link . $matches[1];
        return $new_link;
    }
?>