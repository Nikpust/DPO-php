document.getElementById('btn-reset').addEventListener('click', () => {
    document.getElementById('address').value = '';
    document.getElementById('output').textContent = 'Здесь появится результат 👀';
});

document.getElementById('address').addEventListener('input', () => {
    document.getElementById('output').textContent = 'Здесь появится результат 👀';
});

function geocode() {
    const address = document.getElementById('address').value.trim();
    const output = document.getElementById('output');

    if (address === '') {
        output.textContent = 'Введите адрес!';
        return;
    }

    const form_data = new FormData();
    form_data.append('address', address);

    fetch('api.php', {
        method: 'POST',
        body: form_data
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            output.textContent = `❌ Ошибка: ${data.error}`;
            return;
        }

        let html = `
            <div class="result-line"><span>Адрес: </span> ${data.address} </div>
            <div class="result-line"><span>Координаты: </span> ${data.length}, ${data.width} </div>
        `;

        if (data.flag === 'metro') {
            html += `<div class="result-line"><span>Станция метро: </span> ${data.station} </div>`;
        } else {
            html += `<div class="result-line"><span>Автобусная остановка: </span> ${data.station} </div>`;
        }

        output.innerHTML = html;
    })
    .catch(error => {
        output.textContent = '❌ Ошибка: ' + error.message;
    });
}