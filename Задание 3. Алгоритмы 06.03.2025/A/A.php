<?php
    $array = [];

    while (true) {
        $input = explode("        ", readline());
        if (count($input) < 2) {
            break;
        }

        $banner = $input[0];
        $time = $input[1];

        if (!isset($array[$banner])) {
            $array[$banner] = [];
        }

        $array[$banner][] = $time;

    }

    foreach ($array as $banner => $times) {
        $last_time = strtotime("1970-01-01 00:00:00");
        foreach ($times as $time) {
            if ($last_time < strtotime($time)) {
                $last_time = strtotime($time);
                $l_time = $time;
            }
        }
        echo count($times) . " " . trim($banner) . " " . trim($l_time) . PHP_EOL;
    }
?>