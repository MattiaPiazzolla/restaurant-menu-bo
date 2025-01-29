import './bootstrap';
import '~resources/scss/app.scss';
import '~icons/bootstrap-icons.scss';
import * as bootstrap from 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

import.meta.glob([
    '../img/**'
])

// View switcher
document.getElementById('toggleView').addEventListener('click', function() {
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    if (tableView.classList.contains('hidden')) {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        this.innerHTML = '<i class="fas fa-th-large" title="Switch to Card View"></i>';
    } else {
        tableView.classList.add('hidden');
        cardView.classList.remove('hidden');
        this.innerHTML = '<i class="fas fa-list" title="Switch to Table View"></i>';
    }
});

// Show or hide the button based on scroll position
window.addEventListener('scroll', function () {
    const button = document.getElementById('back-to-top');
    if (window.scrollY > 300) {
        button.style.display = 'block';
    } else {
        button.style.display = 'none';
    }
});

// Scroll to the top of the page when the button is clicked
document.getElementById('back-to-top').addEventListener('click', function () {
    window.scrollTo({
        top: 0,
        behavior: 'smooth' // Smooth scroll effect
    });
});

// Top message auto hide with null check
document.addEventListener('DOMContentLoaded', function() {
    const sliderFill = document.querySelector('.slider-fill');
    if (sliderFill) {
        setTimeout(function(){
            sliderFill.style.width = '0%';
        }, 0);
    }
});