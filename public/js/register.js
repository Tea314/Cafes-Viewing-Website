document.addEventListener('DOMContentLoaded', function () {
    // Toggle for first password field
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const passwordEye = document.getElementById('passwordEye');

    if (togglePassword && passwordInput && passwordEye) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            passwordEye.classList.toggle('bi-eye');
            passwordEye.classList.toggle('bi-eye-slash');
        });
    }

    // Toggle for second password field
    const togglePassword2 = document.getElementById('togglePassword2');
    const password2Input = document.getElementById('password2');
    const password2Eye = document.getElementById('password2Eye');

    if (togglePassword2 && password2Input && password2Eye) {
        togglePassword2.addEventListener('click', function () {
            const type = password2Input.getAttribute('type') === 'password' ? 'text' : 'password';
            password2Input.setAttribute('type', type);
            password2Eye.classList.toggle('bi-eye');
            password2Eye.classList.toggle('bi-eye-slash');
        });
    }
});
