document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const priceInput = form.querySelector('input[name="price"]');
            const stockInput = form.querySelector('input[name="stock"]');

            if (priceInput && parseFloat(priceInput.value) < 0) {
                alert('Harga tidak boleh kurang dari 0');
                e.preventDefault();
                return;
            }

            if (stockInput && parseInt(stockInput.value, 10) < 0) {
                alert('Stok tidak boleh kurang dari 0');
                e.preventDefault();
                return;
            }
        });
    });
});