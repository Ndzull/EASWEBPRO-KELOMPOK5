document.addEventListener("DOMContentLoaded", function () {

    // Navbar
    const toggler = document.getElementById("navToggler");
    const navContent = document.getElementById("navContent");
    const navbarSection = document.getElementById("mainNavbar");

    toggler.addEventListener("click", function (e) {
        e.stopPropagation();
        navContent.classList.toggle("active-side-menu");
    });

    window.onscroll = function () {
        if (window.pageYOffset > 50) {
            navbarSection.classList.add("scrolled");
        } else {
            navbarSection.classList.remove("scrolled");
            navContent.classList.remove("active-side-menu");
        }
    };

    document.addEventListener("click", function (e) {
        const isInside = navContent.contains(e.target) || toggler.contains(e.target);
        if (!isInside) navContent.classList.remove("active-side-menu");
    });

    // Slider
    const sectionTiga = document.querySelector('.section-tiga');
    const slider = sectionTiga.querySelector('.slider');
    const slides = sectionTiga.querySelectorAll('.slide');
    const prevBtn = sectionTiga.querySelector('.prev');
    const nextBtn = sectionTiga.querySelector('.next');
    const dots = sectionTiga.querySelectorAll('.dot');
    const sliderContainer = sectionTiga.querySelector('.slider-container');

    let currentIndex = 0;
    let autoSlideInterval;

    function showSlides(index) {
        if (index >= slides.length) currentIndex = 0;
        else if (index < 0) currentIndex = slides.length - 1;
        else currentIndex = index;
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        dots.forEach((dot, i) => dot.classList.toggle('active', i === currentIndex));
    }

    function startAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(() => showSlides(currentIndex + 1), 4000);
    }

    prevBtn.addEventListener('click', () => { showSlides(currentIndex - 1); startAutoSlide(); });
    nextBtn.addEventListener('click', () => { showSlides(currentIndex + 1); startAutoSlide(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { showSlides(i); startAutoSlide(); }));
    sliderContainer.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
    sliderContainer.addEventListener('mouseleave', startAutoSlide);

    showSlides(0);
    startAutoSlide();

});