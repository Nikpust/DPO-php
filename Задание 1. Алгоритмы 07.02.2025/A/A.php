<?php
    # Структуры хранения ставок и итогов игр
    $stavki = [];
    $itogi = [];

    # Кол-во ставок
    $n = readline();
    
    # Каждую ставку считываем в массив и добавляем ее в ассоциативный массив
    for ($i = 0; $i < $n; $i++) {
        $input = explode(" ", readline());
        $stavki[] = [
            'game_id' => $input[0],
            'bet' => $input[1],
            'result' => $input[2]
        ];
    }

    # Кол-во игр
    $m = readline();

    # Каждую игру считываем в массив и добавляем ее в ассоциативный массив
    for ($i = 0; $i < $m; $i++) {
        $input = explode(" ", readline());
        $game_id = $input[0];
        $itogi [$game_id] = [
            'l_coeff' => $input[1],
            'r_coeff' => $input[2],
            'd_coeff' => $input[3],
            'result' => $input[4]
        ];
    }

    # Баланс игрока
    $sum = 0;
    # Рассматриваем каждую ставку игрока и сверяем с исходом игры
    foreach ($stavki as $stavka) {
        $game_id = $stavka['game_id'];
        $itog = $itogi[$game_id];

        # Проверяем ее итог для нахождения коэф-та
        if ($itog['result'] == 'L') {
            $coeff = $itog['l_coeff'];
        } elseif ($itog['result'] == 'R') {
            $coeff = $itog['r_coeff'];
        } else {
            $coeff = $itog['d_coeff'];
        }
                
        # Сверяем ставку и итог игры, изменяем баланс игрока
        if ($stavka['result'] == $itog['result']) {
            $sum += $stavka['bet'] * $coeff - $stavka['bet'];
        } else {
            $sum -= $stavka['bet'];
        }
    }

    echo $sum;
?>