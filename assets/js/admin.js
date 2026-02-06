/**
 * ACF Panel Generator - Admin JS
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to form submissions
    const form = document.getElementById('generateForm');
    
    if (form) {
        form.addEventListener('submit', function() {
            const buttons = form.querySelectorAll('button[type="submit"]');
            buttons.forEach(button => {
                button.disabled = true;
                button.textContent = '⏳ Generating...';
            });
        });
    }
});
