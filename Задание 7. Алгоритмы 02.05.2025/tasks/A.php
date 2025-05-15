<?php

    # Создаем массивы для выборки
    $protocols = ['http', 'https'];
    $domains = ['ru', 'com'];

    $link = file_get_contents(dirname(__DIR__) . '/tests/data-A.txt');

    # В приоритете https
    if (str_contains($link, $protocols[1])) {
        $link = 'https://' . substr($link, 5);
    } else {
        $link = 'http://' . substr($link, 4);
    }

    # Если в ссылке есть оба домена, то выбираем с наимешим возможным хостом
    if (str_contains($link, $domains[0]) && str_contains($link, $domains[1])) {
        if (strpos($link, $domains[0]) < strpos($link, $domains[1])) {
            $link = preg_replace('/ru/', '.ru/', $link, 1);
        } else {
            $link = preg_replace('/com/', '.com/', $link, 1);
        }
    # Иначе производим замену на первое сопадение (будет наименьший хост)
    } else {
        if (str_contains($link, $domains[0])) {
            $link = preg_replace('/ru/', '.ru/', $link, 1);
        } else {
            $link = preg_replace('/com/', '.com/', $link, 1);
        }
    }

    # Если отсутствует дальнейший url, обрезаем /
    if ($link[-1] === '/') {
        $link = substr($link, 0, -1);
    }

    echo $link;

?>