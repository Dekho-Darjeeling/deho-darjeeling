/* ============================================================
   DEKHO DARJEELING - FULL WEBSITE LOGIC (Enhanced & Corrected 2025)
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    // --- 1. STICKY HEADER SCROLL EFFECT ---
    const header = document.querySelector('.header');
    let ticking = false; // For performance optimization

    const handleHeaderScroll = () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                if (window.scrollY > 50) {
                    header?.classList.add('scrolled'); // Optional chaining for safety
                } else {
                    header?.classList.remove('scrolled');
                }
                ticking = false;
            });
            ticking = true;
        }
    };

    // --- 2. MOBILE HAMBURGER MENU DRAWER ---
    const menuBtn = document.getElementById('menu-btn');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const backdrop = document.getElementById('mobile-backdrop'); // Assuming you have this; add if needed
    const menuIcon = menuBtn?.querySelector('i'); // Get the icon element safely

    if (menuBtn && mobileDrawer) {
        // Toggle Drawer on Button Click
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = mobileDrawer.classList.contains('open');
            if (isOpen) {
                closeDrawer();
            } else {
                openDrawer();
            }
        });

        function openDrawer() {
            mobileDrawer.classList.add('open');
            menuBtn.classList.add('open');
            if (menuIcon) menuIcon.className = 'fa-solid fa-times'; // Change to X icon
            if (backdrop) backdrop.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        function closeDrawer() {
            mobileDrawer.classList.remove('open');
            menuBtn.classList.remove('open');
            if (menuIcon) menuIcon.className = 'fa-solid fa-bars'; // Revert to bars icon
            if (backdrop) backdrop.classList.remove('show');
            document.body.style.overflow = ''; // Restore scroll
        }
    }

    // --- 3. GLOBAL CLICK LISTENER (Close Drawer on Outside Click) ---
    window.addEventListener('click', (e) => {
        if (mobileDrawer && mobileDrawer.classList.contains('open')) {
            if (!mobileDrawer.contains(e.target) && !menuBtn?.contains(e.target)) {
                closeDrawer();
            }
        }
    });

    // Keyboard support: Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileDrawer?.classList.contains('open')) {
            closeDrawer();
        }
    });

    // --- 4. SCROLL REVEAL ANIMATIONS (Intersection Observer) ---
    const animateElements = document.querySelectorAll('[data-animate]');
    let staggerIndex = 0;

    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    try {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate');
                    }, staggerIndex * 100); // Stagger reveals for grids/lists
                    staggerIndex++;
                    // Optional: Uncomment to stop observing once revealed
                    // revealObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        animateElements.forEach(el => {
            revealObserver.observe(el);
        });
    } catch (error) {
        console.warn('IntersectionObserver not supported:', error); // Fallback for older browsers
    }

    // --- 5. FORM VALIDATION HINT (For Contact Forms) ---
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ff6b35'; // Highlight invalid
                    isValid = false;
                } else {
                    field.style.borderColor = '#2e8b57'; // Valid
                }
            });
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // --- 6. WHATSAPP BUTTON BOUNCE ANIMATION (On Page Load) ---
    const whatsappBtn = document.querySelector('.whatsapp-float');
    if (whatsappBtn) {
        // Trigger bounce-in effect on load
        whatsappBtn.style.animation = 'whatsappBounce 1s ease-out';
        // After bounce, switch to the default pulse animation (from CSS)
        setTimeout(() => {
            whatsappBtn.style.animation = 'whatsappPulse 2s infinite';
        }, 1000); // Delay to let bounce finish
    }

    // --- 7. INITIALIZE SCROLL FUNCTIONS ---
    window.addEventListener('scroll', handleHeaderScroll);
    handleHeaderScroll(); // Run once on load
});