<?php
    $array = [];
    
    # Считывние данных
    while (true) {
        $input = explode(" ", readline());
        if (count($input) < 4) {
            break;
        }

        $id = $input[0];
        $name = $input[1];
        $left = $input[2];
        $right = $input[3];

        # Директории храним в виде отдельных элементов 
        $array[] = [
            'id' => $id,
            'name' => $name,
            'left' => $left,
            'right' => $right,
        ];
    }

    # Сортируем пузырькем по левой границе (от меньшего к большему)
    $n = count($array);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($array[$j]['left'] > $array[$j + 1]['left']) {
                $temp = $array[$j];
                $array[$j] = $array[$j + 1];
                $array[$j + 1] = $temp;
            }
        }
    }

    # Организуем обход по отсортированному массив
    $temp = [];
    foreach ($array as $elem) {
        # Проверям, чтобы левая граница не выходила за границу правой,
        # иначе удаляем элемент (тем самым перемещаясь на уровень выше)
        while (!empty($temp) && end($temp)['right'] < $elem['left']) {
            array_pop($temp);
        }

        # Кол-во уровней
        $level = count($temp);
        echo str_repeat('-', $level) . $elem['name'] . "\n";

        $temp[] = $elem;
    }
?>