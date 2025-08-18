document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle_button');
    const aside = document.querySelector('aside');   
    const btnShowPassword =  document.getElementById('btnShowPassword');
    const passwordLogin = document.getElementById('password');

    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            aside.classList.toggle('opened');
        });
    }

    if (btnShowPassword) {
        btnShowPassword.addEventListener('click', function(e) {
            if (passwordLogin.type === 'password') {
                passwordLogin.type = 'text';
                btnShowPassword.classList.remove('bi-eye-fill');
                btnShowPassword.classList.add('bi-eye-slash-fill');
                btnShowPassword.setAttribute('title', 'Ocultar senha')
            } else {
                passwordLogin.type = 'password';
                btnShowPassword.classList.remove('bi-eye-slash-fill');
                btnShowPassword.classList.add('bi-eye-fill');
                btnShowPassword.setAttribute('title', 'Mostrar senha')
            }
        })
    }
});