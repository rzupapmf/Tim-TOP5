require('dotenv').config();

console.log('DB_HOST:', process.env.DB_HOST);
console.log('JWT_SECRET:', process.env.JWT_SECRET);



const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Ovaj kod nam radi animaciju, izmjena klikom na gumb "Sign Up"