<script>
setTimeout(function(){

    let alerts = document.querySelectorAll('.alert');

    alerts.forEach(function(alert){
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
    });

}, 3000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>