
document.addEventListener('DOMContentLoaded', function() {
    var buyButtons = document.querySelectorAll('.btn-primary[data-toggle="modal"]');
    
    buyButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var quantity = button.getAttribute('data-quantity');

            var modalProductId = document.getElementById('modalProductId');
            var modalProductName = document.getElementById('modalProductName');
            var modalQuantity = document.getElementById('modalQuantity');
            var quantityHelp = document.getElementById('quantityHelp');

            modalProductId.value = id;
            modalProductName.textContent = name;
            modalQuantity.setAttribute('max', quantity);
            quantityHelp.textContent = 'Quantidade disponível: ' + quantity;
        });
    });
});