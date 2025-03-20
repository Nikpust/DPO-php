<?php
    $array = [];

    while (true) {
        $input = explode(" ", readline());
        if (count($input) < 4) {
            break;
        }

        $id = $input[0];
        $name = $input[1];
        $left = $input[2];
        $right = $input[3];

        $array[] = [
            'id' => $id,
            'name' => $name,
            'left' => $left,
            'right' => $right,
        ];
    }

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

    $temp = [];

    foreach ($array as $elem) {
        while (!empty($temp) && end($temp)['right'] < $elem['left']) {
            array_pop($temp);
        }

        $level = count($temp);
        echo str_repeat('-', $level) . $elem['name'] . "\n";

        $temp[] = $elem;
    }
?>