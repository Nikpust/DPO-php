function showMessage(target, message, isSuccess = false) {
    const box = document.getElementById(`${target}-message`);
    const content = document.getElementById(`${target}-message-content`);

    content.textContent = message;
    box.style.display = 'block';
    box.classList.remove('hidden', 'success', 'error');
    box.classList.add('show', isSuccess ? 'success' : 'error');
}

function closeMessage(target) {
    const box = document.getElementById(`${target}-message`);
    const content = document.getElementById(`${target}-message-content`);

    box.classList.remove('show', 'success', 'error');
    box.style.display = 'none';
    content.textContent = '';
}