document.addEventListener('DOMContentLoaded', () => {
    const scrollLinks = document.querySelectorAll('.scroll-to');

    scrollLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});