document.getElementById('registerForm').addEventListener('submit', function(event) {
    var password = document.getElementById('password').value;
    var passwordError = document.getElementById('passwordError');
    
    if (password.length < 8) {
        passwordError.textContent = 'La contraseña debe tener al menos 8 caracteres.';
        event.preventDefault(); // Evita que el formulario se envíe
    } else {
        passwordError.textContent = ''; // Limpia el mensaje de error
    }