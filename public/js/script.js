document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle_button');
    const aside = document.querySelector('aside');    

    toggleButton.addEventListener('click', function() {
        aside.classList.toggle('opened');
    });
});