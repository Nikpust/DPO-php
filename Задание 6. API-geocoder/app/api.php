<?php
    # Гарантируем отправку JSON
    header('Content-Type: application/json');

    $address = trim($_POST['address']);

    $api_geo_key = getenv('API_GEOCODER');
    $api_search_key = getenv('API_SEARCH');

    if (!$api_geo_key || !$api_search_key) {
        echo json_encode(['error' => 'Проблема с API-ключами']);
        exit;
    }

    # Формируем url для запроса к API-geocoder
    $parameters = [
        'apikey'    => $api_geo_key,    # Ключ доступа к API
        'geocode'   => $address,        # Адрес искомого объекта
        'lang'      => 'ru_RU',         # Язык ответа
        'format'    => 'json',          # Формат ответа
        'results'   => 1                # Кол-во возвр-х объектов (default = 10)
    ];
    $geocoder_url = 'https://geocode-maps.yandex.ru/1.x/?' . http_build_query($parameters);

    # Парсим полученный ответ от API-geocoder "json->php-array"
    $geocoder_data = json_decode(file_get_contents($geocoder_url), true);
    #file_put_contents('echo-api-json.txt', print_r($geocoder_data, true));
    # И извлекаем нужный овтет
    $resp_object = $geocoder_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject'] ?? null;

    if (!$resp_object) {
        echo json_encode(['error' => 'Адрес не найден']);
        exit;
    }

    # Извлекаем координаты и структурированный адрес
    $coordinates = explode(' ', $resp_object['Point']['pos']);
    $length = $coordinates[0];
    $width = $coordinates[1];
    $structured_address = $resp_object['metaDataProperty']['GeocoderMetaData']['Address']['formatted'] ?? '';

    # Снова формируем url для запроса к API-geocoder, но уже для получения
    # станций метро на основе координат (через адрес нельзя)
    $parameters = [
        'apikey'    => $api_geo_key,
        'geocode'   => "$length,$width",
        'lang'      => 'ru_RU',
        'kind'      => 'metro',
        'format'    => 'json',
        'results'   => 1
    ];
    $geocoder_metro_url = 'https://geocode-maps.yandex.ru/1.x/?' . http_build_query($parameters);

    $geocoder_metro_data = json_decode(file_get_contents($geocoder_metro_url), true);
    $station = $geocoder_metro_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'] ?? null;

    $flag = 'metro';

    # Если в городе отсутствует метро, будем искать остановку
    # Принцип тот же, но потребуется Search-API
    if (!$station) {
        $parameters = [
            'apikey'    => $api_search_key,
            'text'      => 'остановка',
            'lang'      => 'ru_RU',
            'type'      => 'biz',
            'll'        => "$length,$width",
            'spn'       => '0.005,0.005',
            'results'   => 1
        ];
        $search_url = 'https://search-maps.yandex.ru/v1/?' . http_build_query($parameters);

        # Так как Search-API имеет ограничения на запросы, сделаем заглушку:
        # Примем JSON без отображения ошибок, и проверим его на валидность
        $search_response = @file_get_contents($search_url);
        # Если не удалось подключиться к Search-API, то игнорируем поиск автобусных остановок
        if ($search_response !== false) {
            $search_data = json_decode($search_response, true);
            $station = $search_data['features'][0]['properties']['name'] ?? 'Станции и остановки в радиусе 500м не найдены';
        } else {
            $station = 'Не найдены';
        }
        
        $flag = 'stop';
    }

    echo json_encode([
        'address'   => $structured_address,
        'length'    => $length,
        'width'     => $width,
        'station'   => $station,
        'flag'      => $flag
    ]);
?>