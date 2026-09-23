$(document).ready(function(){
    // Initialize Slider 1
    var slider1 = $("#slider1");
    slider1.owlCarousel({
        items: 1,
        loop: true,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 4000,
        responsive: {
            0: { items: 2, slideBy: 1, margin: 20 },
            600: { items: 2, slideBy: 1, margin: 20 },
            1000: { items: 4, slideBy: 1, margin: 20 }
        }
    });

    // Custom Navigation for Slider 1
    $("#prev1").click(function () {
        slider1.trigger('prev.owl.carousel');
    });
    $("#next1").click(function () {
        slider1.trigger('next.owl.carousel');
    });

    // Initialize Slider 2
    var slider2 = $(".slider2");
    slider2.owlCarousel({
        items: 1,
        loop: true,
        nav: false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 4000,
        responsive: {
            0: { items: 2, slideBy: 1, margin: 20 },
            600: { items: 2, slideBy: 1, margin: 20 },
            1000: { items: 4, slideBy: 1, margin: 20 }
        }
    });

    // Custom Navigation for Slider 1
    $(".prev2").click(function () {
        slider2.trigger('prev.owl.carousel');
    });
    $(".next2").click(function () {
        slider2.trigger('next.owl.carousel');
    });
});

// Mobile Menu Toggle
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const closeMobileMenu = document.getElementById('closeMobileMenu');
const mobileMenu = document.getElementById('mobileMenu');
const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.add('active');
    mobileMenuOverlay.classList.remove('hidden');
});

closeMobileMenu.addEventListener('click', () => {
    mobileMenu.classList.remove('active');
    mobileMenuOverlay.classList.add('hidden');
});

mobileMenuOverlay.addEventListener('click', () => {
    mobileMenu.classList.remove('active');
    mobileMenuOverlay.classList.add('hidden');
});

function toggleMobileDropdown(id) {
    const dropdown = document.getElementById(id);
    dropdown.classList.toggle('hidden');
}

// Desktop sub-category dropdown: click to open
document.querySelectorAll('.dropdown .dropdown-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const parent = btn.closest('.dropdown');
        document.querySelectorAll('.dropdown.open').forEach((d) => {
            if (d !== parent) d.classList.remove('open');
        });
        parent.classList.toggle('open');
    });
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown.open').forEach((d) => d.classList.remove('open'));
    }
});

// Mobile menu sub-category dropdown: click to open
document.querySelectorAll('.mobile-dropdown-toggle').forEach((btn) => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const menu = btn.nextElementSibling;
        if (menu) menu.classList.toggle('hidden');
        const icon = btn.querySelector('i');
        if (icon) icon.classList.toggle('fa-chevron-up');
    });
});

// // Slider Functionality
// let currentSlide = 0;
// const slides = document.querySelectorAll('.slide');
// const dots = document.querySelectorAll('.slider-dot');

// function showSlide(n) {
//     slides.forEach(slide => slide.classList.remove('active'));
//     dots.forEach(dot => dot.classList.remove('opacity-100'));
//     dots.forEach(dot => dot.classList.add('opacity-50'));

//     currentSlide = (n + slides.length) % slides.length;
//     slides[currentSlide].classList.add('active');
//     dots[currentSlide].classList.remove('opacity-50');
//     dots[currentSlide].classList.add('opacity-100');
// }

// function changeSlide(n) {
//     showSlide(n);
// }

// function nextSlide() {
//     showSlide(currentSlide + 1);
// }

// function prevSlide() {
//     showSlide(currentSlide - 1);
// }

// // Auto slide
// setInterval(() => {
//     nextSlide();
// }, 6000);

// // Initialize first dot
// showSlide(0);


document.querySelectorAll(".auto-slider").forEach((slider) => {
    const slides = slider.children.length;
    let index = 0;

    setInterval(() => {
    index++;
    slider.style.transform = `translateX(-${index * 100}%)`;

    if (index === slides - 1) {
        setTimeout(() => {
        slider.style.transition = "none";
        slider.style.transform = "translateX(0)";
        index = 0;

        setTimeout(() => {
            slider.style.transition = "transform 700ms ease-in-out";
        }, 50);
        }, 700);
    }
    }, 4000);
});

// Alert Script
const Toast = Swal.mixin({
    toast: true,
    position: 'center-center',
    showConfirmButton: false,
    background: '#E5F3FE',
    timer: 2000
});
function cAlert(type, text){
    Toast.fire({
        icon: type,
        title: text
    });
}
