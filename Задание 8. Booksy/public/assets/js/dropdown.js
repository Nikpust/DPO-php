document.addEventListener("DOMContentLoaded", function() {
    const dropdownButton = document.getElementById('drop-button');
    const dropdownMenu = document.getElementById('dropdown-menu');

    dropdownButton.addEventListener('click', function (e) {
        e.preventDefault();
        dropdownMenu.classList.toggle('open');
    });

    document.addEventListener('click', function (event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.remove('open');
        }
    });
});