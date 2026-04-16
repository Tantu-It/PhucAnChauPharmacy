document.addEventListener('DOMContentLoaded', () => {
    // Xử lý tất cả carousel (banner, carousel, và banner-large)
    document.querySelectorAll('.banner, .carousel, .banner-large').forEach(container => {
        const slides = container.querySelectorAll('.banner-slide, .carousel-slide, .slideshow-slide');
        const prevBtn = container.querySelector('.carousel-prev');
        const nextBtn = container.querySelector('.carousel-next');
        let currentIndex = 0;
        let autoSlideInterval;

        const updateSlides = () => {
            slides.forEach((slide, index) => {
                slide.classList.toggle('active', index === currentIndex);
            });
        };

        const startAutoSlide = () => {
            autoSlideInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlides();
            }, 10000);
        };

        const stopAutoSlide = () => {
            clearInterval(autoSlideInterval);
        };

        // Kiểm tra xem nút có tồn tại trước khi gán sự kiện
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoSlide();
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlides();
                startAutoSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoSlide();
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateSlides();
                startAutoSlide();
            });
        }

        // Khởi động tự động slide
        updateSlides();
        startAutoSlide();
    });

    // Back to Top
    const backToTopBtn = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        backToTopBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // Search Input
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            console.log('Tìm kiếm:', e.target.value);
        });
    }

    // Fade-in animation
    const fadeInElements = document.querySelectorAll('.fade-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    fadeInElements.forEach(el => observer.observe(el));
});