<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/css/style.css">
    <script src="static/js/ajax.js"></script>
    <script src="static/js/validation.js"></script>
    <title>Feedback</title>
</head>
<body>
    <div id="request-container">
        <h1>Отправка запроса</h1>
        <form id="form-feedback">
            <div class="form-input-group">
                <label for="full-name">ФИО</label>
                <input type="text" name="full-name" id="full-name" required>
                <span id="full-name-error" class="error-message">Введите корректное ФИО</span>
            </div>
            <div class="form-input-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
                <span id="email-error" class="error-message">Введите корректный E-mail</span>
            </div>
            <div class="form-input-group">
                <label for="phone">Телефон</label>
                <input type="text" name="phone" id="phone" required>
                <span id="phone-error" class="error-message">Введите корректный телефон</span>
            </div>
            <div class="form-input-group">
                <label for="comment">Комментарий</label>
                <textarea name="comment" id="comment" required></textarea>
            </div>
            <div class="form-button-group">
                <button type="reset" class="btn-reset">Очистить</button>
                <button type="submit" class="btn-submit">Отправить</button>
            </div>
        </form>
    </div>
    <div id="response-container">
        <h1>Оставлено сообщение из формы обратной связи!</h1>
        <p>Имя:         <span id="resp-name"></span></p>
        <p>Фамилия:     <span id="resp-surname"></span></p>
        <p>Отчество:    <span id="resp-patronymic"></span></p>
        <p>E-mail:      <span id="resp-email"></span></p>
        <p>Телефон:     <span id="resp-phone"></span></p>
        <p>С вами свяжутся после:</strong> <span id="resp-time"></span></p>
    </div>
</body>
</html>