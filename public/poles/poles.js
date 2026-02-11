document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.pole-card');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const section = card.getAttribute('data-section');
            window.location.href = `formation/${section}.php`;
        });
    });
});
