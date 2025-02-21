<?php
    # Структура хранения вывода
    $for_output = [];

    $input = explode(" ", readline());
    $n = $input[0]; # Кол-во верщин
    $m = $input[1]; # Кол-во дуг

    # Инициализируем псевдо-матрицу смежности
    $graph = [];
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $graph[$i][$j] = 0;
        }
    }

    # Заносим вес дуги в псевдо-матрицу смежности
    while ($m != 0) {
        $input = explode(" ", readline());
        $graph[$input[0]][$input[1]] = $input[2];
        $graph[$input[1]][$input[0]] = $input[2];
        $m--;
    }

    $count_comand = readline();

    while ($count_comand != 0) {
        $input = explode(" ", readline());

        if ($input[2] == "?") {
            $for_output[] = search_path($graph, $n, $input[0], $input[1]);
        } elseif ($input[2] > 0 ) {
            $graph[$input[0]][$input[1]] = $input[2];
            $graph[$input[1]][$input[0]] = $input[2];
        } else {
            $graph[$input[0]][$input[1]] = 0;
            $graph[$input[1]][$input[0]] = 0;
        }

        $count_comand--;
    }

    # Алгоритм Дейкстры
    function search_path($graph, $n, $start, $end) {
        # Инициализация расстояний и массива посещенных вершин
        for ($i = 0; $i < $n; $i++) {
            $dist[$i] = 1000000;
            $visited[$i] = false;
        }
        
        # Начальное расстояние до стартовой вершины
        $dist[$start] = 0;
        
        # Проход по всем веришнам
        for ($i = 0; $i < $n; $i++) {
            $min_dist = 1000000;
            $min_idx = -1;
            
            # Ищем вершину с минимальным расстоянием
            for ($j = 0; $j < $n; $j++) {
                if (!$visited[$j] && $dist[$j] < $min_dist) {
                    $min_dist = $dist[$j];
                    $min_idx = $j;
                }
            }
    
            # Прерываем цикл, если вершина не найдена
            if ($min_idx == -1) {
                break;
            }
            
            # Обновляем расстояния до всех смежных вершин
            $visited[$min_idx] = true;
    
            for ($j = 0; $j < $n; $j++) {
                if ($graph[$min_idx][$j] > 0 && !$visited[$j]) {
                    $new_dist = $dist[$min_idx] + $graph[$min_idx][$j];
                    if ($new_dist < $dist[$j]) {
                        $dist[$j] = $new_dist;
                    }
                }
            }
        }
    
        if ($dist[$end] == 1000000) {
            return -1;
        } else {
            return $dist[$end];
        }
    }

    foreach ($for_output as $i) {
        echo "$i\n";
    }
?>