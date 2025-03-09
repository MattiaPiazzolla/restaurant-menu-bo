import './bootstrap';
import '~resources/scss/app.scss';
import '~icons/bootstrap-icons.scss';
import * as bootstrap from 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';

import.meta.glob([
    '../img/**'
]);

// View switcher
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleView');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    
    if (toggleButton && tableView && cardView) {
        toggleButton.addEventListener('click', function() {
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
    }
    
    // Show or hide the back-to-top button
    const backToTopButton = document.getElementById('back-to-top');
    if (backToTopButton) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        // Scroll to top when button is clicked
        backToTopButton.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Auto hide top message
    const sliderFill = document.querySelector('.slider-fill');
    if (sliderFill) {
        setTimeout(function(){
            sliderFill.style.width = '0%';
        }, 0);
    }
});
