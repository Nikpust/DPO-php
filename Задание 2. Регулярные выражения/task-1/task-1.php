<?php
    $pattern = "/'(-?\d+)'/";
    $string = readline("Введите строку: ");
    echo preg_replace_callback($pattern, "replace", $string);

    function replace($number) {
        return "'" . $number[1]*2 . "'";
    }
?>