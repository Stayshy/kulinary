function calculateServings(originalServings) {
    const servings = parseInt(document.getElementById('servings').value);
    const factor = servings / originalServings;
    const ingredients = document.querySelectorAll('#ingredient-list li');

    ingredients.forEach(item => {
        const originalQuantity = item.dataset.quantity;
        // Предполагаем, что количество в формате "число единица" (например, "200 г")
        const match = originalQuantity.match(/(\d+\.?\d*)\s*(\w+)/);
        if (match) {
            const quantity = parseFloat(match[1]) * factor;
            const unit = match[2];
            item.textContent = `${item.textContent.split(':')[0]}: ${quantity.toFixed(1)} ${unit}`;
        }
    });
}