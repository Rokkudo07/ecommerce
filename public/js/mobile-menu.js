// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const headerNav = document.querySelector('.header-nav');

    if (menuToggle && headerNav) {
        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('active');
            headerNav.classList.toggle('active');
        });

        // Close menu when clicking on a link
        const navLinks = headerNav.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                headerNav.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = headerNav.contains(event.target) || menuToggle.contains(event.target);
            if (!isClickInside && headerNav.classList.contains('active')) {
                menuToggle.classList.remove('active');
                headerNav.classList.remove('active');
            }
        });
    }

    // User menu dropdown
    const userMenuToggle = document.querySelector('.user-menu-toggle');
    const userMenuDropdown = document.querySelector('.user-menu-dropdown');
    const userMenuWrapper = document.querySelector('.user-menu-wrapper');

    if (userMenuToggle && userMenuDropdown && userMenuWrapper) {
        // Toggle on click
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenuDropdown.classList.toggle('active');
        });

        // Keep dropdown open when hovering over wrapper or dropdown
        userMenuWrapper.addEventListener('mouseenter', function() {
            userMenuDropdown.classList.add('active');
        });

        userMenuWrapper.addEventListener('mouseleave', function() {
            userMenuDropdown.classList.remove('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = userMenuWrapper.contains(event.target);
            if (!isClickInside && userMenuDropdown.classList.contains('active')) {
                userMenuDropdown.classList.remove('active');
            }
        });
    }
});
