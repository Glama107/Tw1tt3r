// Show/hide profile dropdown menu on click
const dropdownBtn = document.getElementById('profile-dropdown-btn');
const dropdownMenu = document.getElementById('profile-dropdown-menu');

dropdownBtn.addEventListener('click', () => {
    dropdownMenu.classList.toggle('hidden');
});

// Make navbar sticky on scroll
const navbar = document.getElementById('navbar');
const navbarOffsetTop = navbar.offsetTop;

window.addEventListener('scroll', () => {
    if (window.pageYOffset >= navbarOffsetTop) {
        navbar.classList.add('sticky');
    } else {
        navbar.classList.remove('sticky');
    }
});