/* assets/js/services/script.js */

document.addEventListener('DOMContentLoaded', function() {
    var serviceButtons = document.querySelectorAll('.btn-primary[data-toggle="modal"]');
    
    serviceButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');

            var modalServiceId = document.getElementById('modalServiceId');
            var modalServiceName = document.getElementById('modalServiceName');

            modalServiceId.value = id;
            modalServiceName.textContent = name;
        });
    });
});
