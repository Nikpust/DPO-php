document.addEventListener('click', function (event) {
    const modal = document.getElementById('modal-overlay-add');
    if (modal.classList.contains('open') && event.target === modal) {
        closeModalAdd(false);
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeModalAdd(false);
    }
});

function openModalAdd() {
    const overlay = document.getElementById('modal-overlay-add');
    overlay.classList.add('open');
    document.body.classList.add('blurred');
}

function closeModalAdd(reload = true) {
    const overlay = document.getElementById('modal-overlay-add');
    const form = document.getElementById('book-form-add');

    overlay.classList.remove('open');
    document.body.classList.remove('blurred');

    form.reset();
    clearPreviews();
    closeMessage('bookadd');
    if (reload) window.location.reload();
}




document.addEventListener('click', function (event) {
    const modal = document.getElementById('modal-overlay-edit');
    if (modal.classList.contains('open') && event.target === modal) {
        closeModalEdit();
    }
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeModalEdit();
    }
});

function openModalEdit() {
    const overlay = document.getElementById('modal-overlay-edit');
    overlay.classList.add('open');
    document.body.classList.add('blurred');
}

function closeModalEdit() {
    const overlay = document.getElementById('modal-overlay-edit');
    const form = document.getElementById('book-form-edit');

    overlay.classList.remove('open');
    document.body.classList.remove('blurred');

    form.reset();
    clearPreviews();
    closeMessage('bookedit');
}




document.addEventListener('DOMContentLoaded', () => {
    openModalMore();
});

function openModalMore() {
    const moreButtons = document.querySelectorAll('.btn-more');
    moreButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const bookId = this.dataset.id;

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
                    const currentUserId = document.body.dataset.userId;

                    document.getElementById('book-title').textContent = result.book.book_title;
                    document.getElementById('book-author').textContent = result.book.book_author;
                    document.getElementById('book-date-added').textContent = (result.book.book_created_at).split('.')[0];
                    document.getElementById('book-creator').textContent = result.book.user_fullname;
                    
                    if (result.book.user_id == currentUserId) {
                        const dateRead = document.getElementById('book-read-date');
                        if (dateRead) {
                            dateRead.textContent = result.book.book_read_date;
                            dateRead.parentElement.style.display = 'block';
                        }
                    } else {
                        const dateRead = document.getElementById('book-read-date');
                        if (dateRead) {
                            dateRead.parentElement.style.display = 'none';
                        }
                    }
                
                    const overlay = document.getElementById('modal-more-overlay');
                    overlay.classList.add('open');
                    document.body.classList.add('blurred');
                } else {
                    showMessage('book', 'Ошибка при получении данных книги. Попробуйте позже.');
                }
            });
        });
    });
}

function closeModalMore() {
    const overlay = document.getElementById('modal-more-overlay');

    overlay.classList.remove('open');
    document.body.classList.remove('blurred');

    document.getElementById('book-title').textContent = '';
    document.getElementById('book-author').textContent = '';
    document.getElementById('book-date-added').textContent = '';
    
    const dateRead = document.getElementById('book-read-date');
    if (dateRead) {
        dateRead.textContent = '';
    }
}