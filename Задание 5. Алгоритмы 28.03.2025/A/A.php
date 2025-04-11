<?php
    # Считываем количество рейсов и инициализируем массив
    $count = readline();
    $flights = [];

    # Считываем данные о рейсах
    for ($i = 0; $i < $count; $i++) {
        $input = explode(" ", readline());      # Разделяем строку по пробелам
        $input_dep = explode("_", $input[0]);   # Разделяем дату и время вылета
        $input_arr = explode("_", $input[2]);   # Разделяем дату и время прилета
        
        # Заносим данные в массив
        $flights[] = [
            'dep_date' => $input_dep[0],
            'dep_time' => $input_dep[1],
            'dep_zone' => $input[1],
            'arr_date' => $input_arr[0],
            'arr_time' => $input_arr[1],
            'arr_zone' => $input[3],
        ];
    }

    # Проходимся по каждому рейсу
    foreach ($flights as $flight) {
        # Переводим время вылета/прилета в секунды и приводим к одному часовому поясу
        $dep = strtotime($flight['dep_date'] . ' ' . $flight['dep_time']) - $flight['dep_zone'] * 3600;
        $arr = strtotime($flight['arr_date'] . ' ' . $flight['arr_time']) - $flight['arr_zone'] * 3600;
        # Выводим время полета
        echo $arr - $dep . PHP_EOL;
    }
?>