document.addEventListener('DOMContentLoaded', () => {
    // Обеспечиваем загрузку всех функций после загрузки DOM
    initRegisterForm();
    initLoginForm();
    initAddBookForm();
    initDeleteBook();
    initGetBook();
    initDownloadBook();
});

function initRegisterForm() {
    const formRegister = document.getElementById('register-form');
    if (!formRegister) return;

    formRegister.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formRegister);
        const submitButton = document.getElementById('btn-register');
        submitButton.disabled = true;

        fetch('/register', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            const success = result.success;
            if (success) {
                formRegister.reset();
                document.getElementById('to-signin').click();
            } else {
                showMessage('register', result.message);
            }
        })
        .catch(() => {
            showMessage('register', 'Ошибка при регистрации. Попробуйте позже.');
        })
        .finally(() => {
            submitButton.disabled = false;
        });
    });
}

function initLoginForm() {
    const formLogin = document.getElementById('login-form');
    if (!formLogin) return;

    formLogin.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formLogin);
        const submitButton = document.getElementById('btn-login');
        submitButton.disabled = true;

        fetch('/login', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            const success = result.success;
            if (success) {
                window.location.href = result.redirect;
            } else {
                showMessage('login', result.message);
            }
        })
        .catch(() => {
            showMessage('login', 'Ошибка при входе. Попробуйте позже.');
        })
        .finally(() => {
            submitButton.disabled = false;
        });
    });
}

function initAddBookForm() {
    const formAddBook = document.getElementById('book-form-add');
    if (!formAddBook) return;

    formAddBook.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(formAddBook);
        const submitButton = document.getElementById('btn-add-book');
        submitButton.disabled = true;
        let clear = true; // Очистка картинки

        fetch('/profile/add-book', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(result => {
            const success = result.success;
            if (success) {
                formAddBook.reset();
                showMessage('bookadd', result.message, true);
            } else {
                clear = false; // Не очищаем картинку, если произошла ошибка (превышен размер)
                showMessage('bookadd', result.message);
            }
        })
        .catch(() => {
            showMessage('bookadd', 'Ошибка при добавлении книги. Попробуйте позже.');
        })
        .finally(() => {
            submitButton.disabled = false;
            if (clear) clearPreviews();
        });
    });
}

function initDeleteBook() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            if (!confirm('Удалить книгу?')) return;

            const bookId = this.dataset.id;

            fetch('/profile/delete-book', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: bookId })
            })
            .then(res => res.json())
            .then(result => {
                const success = result.success;
                if (success) {
                    window.location.reload();
                    alert(result.message);
                }
            })
            .catch(() => {
                alert('Ошибка при удалении книги. Попробуйте позже.');
            });
        });
    });
}

function initGetBook() {
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const bookId = this.dataset.id;
            const form = document.getElementById('book-form-edit');

            fetch('/profile/get-book', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify({ id: bookId })
            })
            .then(res => res.json())
            .then(result => {
                const success = result.success;
                if (success) {
                    document.getElementById('title').value = result.book.book_title;
                    document.getElementById('author').value = result.book.book_author;
                    document.getElementById('read-date').value = result.book.book_read_date;
                    document.getElementById('allow_download').checked = result.book.book_allow_download;

                    clearPreviews();
                    addPreview('cover', result.book.book_cover_path, 'cover-preview-edit');
                    addPreview('file', result.book.book_file_path, 'file-preview-edit');
                    
                    initUpdateBook(bookId);
                }
            })
            .catch(() => {
                showMessage('bookedit', 'Ошибка при редактировании книги. Попробуйте позже.');
            });
        });
    });
}

function initUpdateBook(bookId) {
    const formEditBook = document.getElementById('book-form-edit');

    // Клонируем форму и заменяем старую копией, чтобы удалить все старые обработчики событий
    // Предотвращает повторную привязку обработчиков при многократном открытии формы
    const form = formEditBook.cloneNode(true);
    formEditBook.parentNode.replaceChild(form, formEditBook);
    
    // При выборе новой обложки — показать ее превью
    const coverInput = form.querySelector('#cover-edit');
    if (coverInput) {
        coverInput.addEventListener('change', e => {
            showCoverPreview(e.target.files[0], 'cover-preview-edit');
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('id', bookId);
        
        const submitButton = document.getElementById('btn-update-book');
        submitButton.disabled = true;

        // Проверка на размер файла (для вывода ошибки)
        let close = true;

        fetch('/profile/update-book', {
            method: 'POST',
            body:   formData
        })
        .then(res => res.json())
        .then(result => {
            const success = result.success;
            if (success) {
                alert(result.message);
                form.reset();
            } else {
                close = false;
                showMessage('bookedit', result.message);
            }
        })
        .catch(() => {
            showMessage('bookedit', 'Ошибка при обновлении книги. Попробуйте позже.');
        })
        .finally(() => {
            submitButton.disabled = false;
            if (close) {
                closeModalEdit();
                window.location.reload();
            }
        });
    });
}

function initDownloadBook() {
    const downloadButtons = document.querySelectorAll('.btn-download');

    downloadButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const bookId = this.dataset.id;

            fetch('/check-book-download', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify({ id: bookId })
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/download-book';
                    form.style.display = 'none';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'bookId';
                    input.value = bookId;
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                } else {
                    alert(result.message);
                }
            })
            .catch(() => {
                alert('Ошибка сервера. Попробуйте позже.');
            });
        });
    });
}