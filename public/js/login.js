function toggleForm(form) {
    var loginForm = document.getElementById('login');
    var registerForm = document.getElementById('register');
    var btn = document.getElementById('btn');

    if (form === 'login') {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        btn.style.left = '0';
    } else {
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
        btn.style.left = '50%';
    }
}

// Set default form to display
document.addEventListener("DOMContentLoaded", function () {
    toggleForm('login');
});
