<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Geocoder</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <div class="container">
        <h1>Geocoder - найдем где вы!</h1>
        <input type="text" id="address" placeholder="Введите примерный адрес...">
        <div class="buttons">
            <button id = "btn-reset" class="btn-reset">Очистить</button>
            <button id = "btn-submit" onclick="geocode()">Найти</button>
        </div>
        <p id="output">Здесь появится результат 👀</p>
    </div>

    <script src="static/script.js"></script>
</body>
</html>