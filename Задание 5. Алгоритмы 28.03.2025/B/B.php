<?php
    # Считываем пути входных файлов
    $file = $argv[1];
    $file_sections = __DIR__ . "/" . $file . "_sections.xml";
    $file_products = __DIR__ . "/" . $file . "_products.xml";
    # Формируем файл для вывода
    $output_file = __DIR__ . '/output.xml';

    # Создаем объекты типа SimpleXMLElement
    $sections_xml = simplexml_load_file($file_sections);
    $products_xml = simplexml_load_file($file_products);

    # Инициализируем массив разделов
    $sections = [];
    # Проходимся по каждому разделу xml-файла
    foreach ($sections_xml->Раздел as $section) {
        # id-раздела сохраняем в качестве ключа
        $id = (string)$section->Ид;
        # По этому ключу сохраняем информацию о разделе
        $sections[$id] = [
            'Ид' => $id,
            'Наименование' => (string)$section->Наименование,
            'Товары' => [] # Будет добавлять товары для этого раздела
        ];
    }

    # Если есть список товаров
    if (isset($products_xml->Товар)) {
        # Проходимся по каждому из товаров
        foreach ($products_xml->Товар as $product) {
            # Сохраняем также инфомрацию о нем
            $product_data = [
                'Ид' => (string)$product->Ид,
                'Наименование' => (string)$product->Наименование,
                'Артикул' => (string)$product->Артикул
            ];
            
            # Определяем к какому разделу относится товар
            foreach ($product->Разделы->ИдРаздела as $section_id) {
                # Закрепляем товары за конкретным разделом
                $s_id = (string)$section_id;
                $sections[$s_id]['Товары'][] = $product_data;
            }
        }
    }

    # Создаем новый xml-файл
    $output = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><ЭлементыКаталога></ЭлементыКаталога>');
    # Создаем корневой элемент XML-дерева
    $xml_element = $output->addChild('Разделы');

    # Будем переносить из массива в xml-файл
    foreach ($sections as $section) {
        # В текущем элементе добавляем новую секцию
        $section_node = $xml_element->addChild('Раздел');
        # Записываем все в текущую секцию
        $section_node->addChild('Ид', $section['Ид']);
        $section_node->addChild('Наименование', $section['Наименование']);
        
        # Вложенный список товаров
        $section_node = $section_node->addChild('Товары');
        foreach ($section['Товары'] as $product) {
            $product_node = $section_node->addChild('Товар');
            $product_node->addChild('Ид', $product['Ид']);
            $product_node->addChild('Наименование', $product['Наименование']);
            $product_node->addChild('Артикул', $product['Артикул']);
        }
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;               # Убираем лишние пробелы
    $dom->formatOutput = true;                      # Авто форматирование
    $dom->loadXML($output->asXML());                # Загружаем xml-строку из объекта SimpleXMLElement
    $save_xml = $dom->saveXML();                    # Сохраняем отформатированный XML
    file_put_contents($output_file, $save_xml);

    $xml = file_get_contents($output_file);
    $xml = str_replace("  ", "    ", $xml);
    echo $xml;
?>