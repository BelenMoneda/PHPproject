document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.producto img').forEach(function(img) {
        img.addEventListener('click', function() {
            window.location.href = 'detalle_producto.php?nombre=' + encodeURIComponent(this.alt);
        });
    });
});
