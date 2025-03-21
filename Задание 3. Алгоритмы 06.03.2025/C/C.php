<?php
    $array = [];
    $sum = 0;

    # Считывание данных
    while (true) {
        $input = explode(" ", readline());

        if (count($input) < 2) {
            break;
        }

        # Помещаем в формате ключ-значение
        $key = $input[0];
        $value = $input[1];

        # При добавлении находим сразу сумму
        $array[$key] = $value;
        $sum += $value;
    }

    # Проходимся по каждому элементу и вычисляем его временную долю
    foreach($array as $key => $value) {
        $time = $value / $sum;
        echo $key . " " . $time . PHP_EOL;
    }
    # P.s. Погрешность может составлять 0.0013, в связи со странным округлением 1С 
?>