document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-feedback");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const form_data = new FormData(form);

        fetch("form-handler.php", {
            method: "POST",
            body: form_data
        })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById("request-container").style.display = "none";
                document.getElementById("response-container").style.display = "block";

                document.getElementById("resp-name").innerText = data.name;
                document.getElementById("resp-surname").innerText = data.surname;
                document.getElementById("resp-patronymic").innerText = data.patronymic;
                document.getElementById("resp-email").innerText = data.email;
                document.getElementById("resp-phone").innerText = data.phone;
                document.getElementById("resp-time").innerText = data.time;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Ошибка запроса:", error);
        });
    });
});
