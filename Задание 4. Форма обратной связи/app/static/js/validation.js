// Ожидаем полной загрузки DOM перед выполнением скрипта
document.addEventListener("DOMContentLoaded", function () {
    // Получаем элементы формы по их ID
    const full_name_input = document.getElementById("full-name");
    const full_name_error = document.getElementById("full-name-error");
    const phone_input = document.getElementById("phone");
    const phone_error = document.getElementById("phone-error");
    const email_input = document.getElementById("email");
    const email_error = document.getElementById("email-error");

    // Обработчик ввода для поля "ФИО"
    full_name_input.addEventListener("input", function () {
        // Удаляем все символы, кроме букв и пробелов
        full_name_input.value = full_name_input.value.replace(/[^a-zA-Zа-яА-ЯёЁ\s]/g, "");
        const full_name = full_name_input.value;

        // Если поле пустое — сбрасываем ошибки и стили
        if (full_name == "") {
            full_name_input.style.borderColor = "";
            full_name_error.style.display = "none";
            full_name_input.classList.remove("input-error");
            return;
        }

        // Проверяем формат ФИО: минимум два слова, между словами только одиночные пробелы
        const patern = /^[a-zA-Zа-яА-ЯёЁ]+(\s[a-zA-Zа-яА-ЯёЁ]+)+$/.test(full_name);
        const spaces = !/\s{2,}/.test(full_name);
        let space_count = 0;
        
        if (full_name.match(/\s/g)) {
            space_count = full_name.match(/\s/g).length;
        }

        // Условие корректного ФИО
        if (patern && spaces && space_count >= 1) {
            full_name_error.style.display = "none";
            full_name_input.classList.remove("input-error");
        } else {
            full_name_input.classList.add("input-error");
            full_name_error.style.display = "block";
        }
    });

    // Обработчик ввода для поля "Телефон"
    phone_input.addEventListener("input", function () {
        // Удаляем все символы, кроме цифр
        let numbers = phone_input.value.replace(/\D/g, "");
        let formatted_phone = "+7 ";

        // Форматируем номер телефона в формат +7 (XXX) XXX-XX-XX
        if (numbers.length > 1) {
            formatted_phone += "(" + numbers.substring(1, 4);
        }
        if (numbers.length >= 5) {
            formatted_phone += ") " + numbers.substring(4, 7);
        }
        if (numbers.length >= 8) {
            formatted_phone += "-" + numbers.substring(7, 9);
        }
        if (numbers.length >= 10) {
            formatted_phone += "-" + numbers.substring(9, 11);
        }

        phone_input.value = formatted_phone;
    });

    // При фокусе на поле телефона автоматически подставляется начало "+7 ("
    phone_input.addEventListener("focus", function () {
        if (phone_input.value == "") {
            phone_input.value = "+7 (";
        }
    });

    // При выходе из поля телефона проверяем его длину и отображаем/убираем ошибку
    phone_input.addEventListener("blur", function () {
        if (phone_input.value == "+7 (" || phone_input.value == "+7 ") {
            phone_input.value = "";
            phone_input.classList.remove("input-error");
            phone_error.style.display = "none";
        } else if (phone_input.value.length < 18) {
            phone_input.classList.add("input-error");
            phone_error.style.display = "block";
        } else {
            phone_input.classList.remove("input-error");
            phone_error.style.display = "none";
        }
    });

    // Обработчик ввода для поля "Email"
    email_input.addEventListener("input", function () {
        // Удаляем недопустимые символы (кроме латиницы, цифр, -, _, ., @)
        email_input.value = email_input.value.replace(/[^a-zA-Z0-9_.-@]/g, "");
        email_input.value = email_input.value.replace(/[:;]/g, "");
        const email_pattern = /^[A-Za-z0-9][A-Za-z0-9_.-]{2,29}@[A-Za-z]{2,30}\.[a-z]{2,10}/;
        const email = email_input.value;

        // Если поле пустое — убираем ошибку
        if (email == "") {
            email_input.classList.remove("input-error");
            email_error.style.display = "none";
            return;
        }

        // Проверка соответствия шаблону
        if (email_pattern.test(email)) {
            email_input.classList.remove("input-error");
            email_error.style.display = "none";
        } else {
            email_input.classList.add("input-error");
            email_error.style.display = "block";
        }
    });
});