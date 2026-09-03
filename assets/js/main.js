document.addEventListener('DOMContentLoaded', () => {
    // Basic interaction handling for front-end cart triggers
    const cartButtons = document.querySelectorAll('.add-to-cart-btn');

    cartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const originalHTML = button.innerHTML;
            button.classList.replace('btn-outline-danger', 'btn-success');
            button.innerHTML = '<i class="bi bi-check-lg"></i>';
            
            setTimeout(() => {
                button.classList.replace('btn-success', 'btn-outline-danger');
                button.innerHTML = originalHTML;
            }, 1200);
        });
    });
});