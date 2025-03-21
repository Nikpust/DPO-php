<?php
    $array = [];
    # Считывние данных
    while (true) {
        $input = explode("        ", readline());
        if (count($input) < 2) {
            break;
        }

        # Разбиваем на элементы ключ(баннер)-значение(время)
        $banner = $input[0];
        $time = $input[1];

        # Если баннер еще не добавлен в массив — создаем для него пустой массив
        if (!isset($array[$banner])) {
            $array[$banner] = [];
        }

        # Помещаем значение по ключу
        $array[$banner][] = $time;

    }

    # Проходимся по каждому баннеру и его списку показов
    foreach ($array as $banner => $times) {
        # За последнее время принимаем самое ранее
        $last_time = strtotime("1970-01-01 00:00:00");
        # Находим последнее время публикации баннера
        foreach ($times as $time) {
            if ($last_time < strtotime($time)) {
                $last_time = strtotime($time);
                $l_time = $time;
            }
        }
        echo count($times) . " " . trim($banner) . " " . trim($l_time) . PHP_EOL;
    }
?>