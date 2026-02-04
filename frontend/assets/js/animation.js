/* ==================== FULL WEBSITE JAVASCRIPT ==================== */

document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. MOBILE HAMBURGER MENU LOGIC ---
    const menuBtn = document.getElementById('menu-btn');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const closeBtn = document.getElementById('drawer-close');

    if (menuBtn && mobileDrawer) {
        // Open Menu
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileDrawer.classList.add('active');
        });

        // Close Menu via 'X' button
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                mobileDrawer.classList.remove('active');
            });
        }

        // Close Menu when clicking outside the drawer
        document.addEventListener('click', (e) => {
            if (!mobileDrawer.contains(e.target) && !menuBtn.contains(e.target)) {
                mobileDrawer.classList.remove('active');
            }
        });
    }


    // --- 2. HEADER SCROLL EFFECT (Transparent to Solid) ---
    const header = document.querySelector('.header');
    
    const handleScroll = () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    };

    window.addEventListener('scroll', handleScroll);


    // --- 3. SCROLL REVEAL ANIMATIONS (Modern Intersection Observer) ---
    // This is more performant than getBoundingClientRect for 2026 browsers
    const animateElements = document.querySelectorAll('[data-animate]');

    const observerOptions = {
        threshold: 0.15, // Trigger when 15% of the element is visible
        rootMargin: "0px 0px -50px 0px" // Triggers slightly before it enters the viewport
    };

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                // Optional: Stop observing once the animation is done
                // sectionObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    animateElements.forEach(el => {
        sectionObserver.observe(el);
    });


    // --- 4. FORM SUBMISSION (Tours Page Fix) ---
    const tourForm = document.getElementById('tourForm');
    if (tourForm) {
        tourForm.addEventListener('submit', (e) => {
            // This allows the form to work while you test your backend
            console.log("Form submission triggered...");
        });
    }
});
