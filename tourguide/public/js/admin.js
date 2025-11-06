/*!
 * Admin Dashboard Scripts
 * Based on StartBootstrap SB Admin
 */

(function() {
    'use strict';

    window.addEventListener('DOMContentLoaded', event => {
        // Toggle the side navigation
        const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            // Uncomment Below to persist sidebar toggle between refreshes
            if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
                document.body.classList.toggle('sb-sidenav-toggled');
            }
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            });
        }

        // Set active nav link based on current URL
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.sb-sidenav-menu .nav-link');
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href.split('admin/')[1] || '')) {
                link.classList.add('active');
            }
        });
    });
})();

