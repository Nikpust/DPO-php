<?php
    $array = [];
    $sum = 0;

    while (true) {
        $input = explode(" ", readline());

        if (count($input) < 2) {
            break;
        }

        $key = $input[0];
        $value = $input[1];

        $array[$key] = $value;
        $sum += $value;
    }

    foreach($array as $key => $value) {
        $time = $value / $sum;
        echo $key . " " . $time . PHP_EOL;
    }
?>