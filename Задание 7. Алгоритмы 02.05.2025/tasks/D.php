<?php

    date_default_timezone_set('Europe/Moscow');

    $input = file_get_contents(dirname(__DIR__) . '/tests/data-D.txt');

    $lines = explode("\n", trim($input));
    $items = [];

    # Разбираем входные данные на части
    foreach ($lines as $line) {
        $params = explode(';', $line);
        $id = $params[0];
        $items[$id] = [
            'url' => $params[1],
            'parent' => $params[2],
            'time' => $params[3],
            'max_time' => $params[3] # Может измениться, если есть потомки
        ];
    }

    # Обновляем как раз таки временную метку
    foreach ($items as $item) {
        $time = $item['time'];
        $parent = $item['parent'];
        # Запускаем цикл вверх по родителям
        while ($parent != 0 && isset($items[$parent])) {
            # Если у родителя время меньше, чем у потомка, то обновляем
            if ($items[$parent]['max_time'] < $time) {
                $items[$parent]['max_time'] = $time;
            }
            # Поднимаемся выше и повторяем, пока не дойдем до корня
            $parent = $items[$parent]['parent'];
        }
    }

    # Сортируем id по возрастанию
    ksort($items);

    # Формируем "XML"
    echo "<urlset xmlns=\"https://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach ($items as $item) {
        $isoTime = date('c', $item['max_time']);    # Формат даты в ISO 8601
        echo "  <url>\n";
        echo "    <loc>" . $item['url'] . "</loc>\n";
        echo "    <lastmod>" . $isoTime . "</lastmod>\n";
        echo "  </url>\n";
    }

    echo "</urlset>\n";

?>