document.addEventListener('DOMContentLoaded', () => {    
    const container = document.getElementById('auth-container');
    const signUpBtn = document.getElementById('to-signup');
    const signInBtn = document.getElementById('to-signin');

    function switchPanel(toSignup = false) {
        container.classList.toggle('right-panel-active', toSignup);
        clearForms();
    }

    signUpBtn?.addEventListener('click', () => {
        switchPanel(true);
        history.pushState(null, '', '/register');
    });

    signInBtn?.addEventListener('click', () => {
        switchPanel(false);
        history.pushState(null, '', '/login');
    });
});

function clearForms() {
    const formRegister = document.getElementById('register-form');
    const formLogin = document.getElementById('login-form');

    formRegister?.reset();
    formLogin?.reset();

    closeMessage('register');
    closeMessage('login');
}
