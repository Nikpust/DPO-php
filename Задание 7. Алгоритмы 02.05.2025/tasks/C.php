<?php

    function SQL($json) {
        # Принимаем и декодируем JSON
        $data = json_decode($json, true);

        if (!empty($data['select'])) {
            $select = 'select ' . implode(', ', $data['select']);
        } else {
            $select = 'select *';
        }

        $from = 'from ' . $data['from'];

        $where = '';
        if (!empty($data['where'])) {
            $where = 'where ' . parseWhere($data['where']);
        }

        $order = '';
        if (!empty($data['order'])) {
            $field = array_key_first($data['order']);
            $direction = $data['order'][$field];
            $order = 'order by ' . $field . ' ' . $direction;
        }

        $limit = '';
        if (!empty($data['limit'])) {
            $limit = 'limit ' . $data['limit'];
        }

        return $select . PHP_EOL . $from . PHP_EOL . $where . PHP_EOL . $order . PHP_EOL . $limit;
    }

    function parseWhere($conditions, $logical = 'and') {
        $parts = [];

        foreach ($conditions as $key => $value) {
            # Обработка вложенных групп условий "and_" / "or_"
            if (str_starts_with($key, 'and') || str_starts_with($key, 'or')) {
                if (str_starts_with($key, 'or')) {
                    $subLogical = 'or';
                } else {
                    $subLogical = 'and';
                }

                # Рекурсивно обрабатываем вложенную группу
                $subConditions = parseWhere($value, $subLogical);
                # Добавляем в список
                $parts[] = '(' . $subConditions . ')';
                continue;
            }

            # Разбираем оператор и имя поля из ключа
            $operator = '';
            $field = '';

            $firstTwo = substr($key, 0, 2); # <=, >=
            $firstOne = $key[0];            # <, >, =, !

            switch ($firstTwo) {
                case '<=':
                    $operator = $firstTwo;
                    $field = substr($key, 2);
                    break;
                case '>=':
                    $operator = $firstTwo;
                    $field = substr($key, 2);
                    break;

                default:
                    switch ($firstOne) {
                        case '<':
                            $operator = $firstOne;
                            $field = substr($key, 1);
                            break;
                        case '>':
                            $operator = $firstOne;
                            $field = substr($key, 1);
                            break;
                        case '=':
                            $operator = $firstOne;
                            $field = substr($key, 1);
                            break;
                        case '!':
                            $operator = $firstOne;
                            $field = substr($key, 1);
                            break;

                        default:
                            $field = $key;
                            break;
                    }
                    break;
            }

            # Подготовка значения для SQL
            if (is_string($value)) {
                $valueStr = "'" . addslashes($value) . "'";
            } elseif (is_bool($value)) {
                $valueStr = $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $valueStr = 'null';
            } else {
                $valueStr = $value;
            }

            # Формируем условие
            $condition = '';

            if ($operator === '<' || $operator === '>' || $operator === '<=' || $operator === '>=') {
                $condition = "$field $operator $valueStr";
            } elseif ($operator === '=') {
                if ($valueStr === 'null') {
                    $condition = "$field is null";
                } elseif ($valueStr === 'true' || $valueStr === 'false') {
                    $condition = "$field is $valueStr";
                } else {
                    $condition = "$field = $valueStr";
                }
            } elseif ($operator === '!') {
                if ($valueStr === 'null') {
                    $condition = "$field is not null";
                } elseif ($valueStr === 'true' || $valueStr === 'false') {
                    $condition = "$field is not $valueStr";
                } else {
                    $condition = "$field != $valueStr";
                }
            } else {
                if (is_string($value)) {
                    $condition = "$field like $valueStr";
                } elseif ($valueStr === 'null') {
                    $condition = "$field is null";
                } elseif ($valueStr === 'true' || $valueStr === 'false') {
                    $condition = "$field is $valueStr";
                } else {
                    $condition = "$field = $valueStr";
                }
            }

            $parts[] = $condition;
        }
        return implode(" $logical ", $parts);
    }

    $json = file_get_contents(dirname(__DIR__) . '/tests/data-C.txt');
    
    echo SQL($json);

?>