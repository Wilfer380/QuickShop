document.querySelectorAll('[data-product-gallery]').forEach((thumbnailButton) => {
    thumbnailButton.addEventListener('click', () => {
        const productCard = thumbnailButton.closest('.product');
        const previewImage = productCard?.querySelector('.product-preview');

        if (!previewImage) {
            return;
        }

        previewImage.src = thumbnailButton.dataset.preview;

        productCard.querySelectorAll('[data-product-gallery]').forEach((button) => {
            button.classList.remove('active');
        });

        thumbnailButton.classList.add('active');
    });
});
