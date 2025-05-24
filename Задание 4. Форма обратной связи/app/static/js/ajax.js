document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-feedback");

    // Вешиваем обработчик события отправки формы
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        // Собираем данные формы в формате FormData
        const form_data = new FormData(form);

        // Отправляем данные на сервер через fetch
        fetch("form-handler.php", {
            method: "POST",     // Метод запроса — POST
            body: form_data     // Тело запроса — данные формы
        })
        .then(response => {
            return response.json();     // Преобразуем ответ в JSON
        })
        .then(data => {
            // Если запрос выполнен успешно и сервер вернул true
            if (data.success) {
                // Скрываем форму и показываем контейнер с ответом
                document.getElementById("request-container").style.display = "none";
                document.getElementById("response-container").style.display = "block";

                // Подставляем данные, полученные от сервера, в соответствующие элементы на странице
                document.getElementById("resp-name").innerText = data.name;
                document.getElementById("resp-surname").innerText = data.surname;
                document.getElementById("resp-patronymic").innerText = data.patronymic;
                document.getElementById("resp-email").innerText = data.email;
                document.getElementById("resp-phone").innerText = data.phone;
                document.getElementById("resp-time").innerText = data.time;
            } else {
                // Если сервер вернул ошибку — показываем сообщение пользователю
                alert(data.message);
            }
        })
        .catch(error => {
             // Обработка сетевых или других ошибок запроса
            console.error("Ошибка запроса:", error);
        });
    });
});