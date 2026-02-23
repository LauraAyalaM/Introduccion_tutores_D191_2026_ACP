<?php include "../includes/header.php"; ?>

<div class="card p-4 mx-auto" style="max-width:400px;">
<h4 class="text-center">Login</h4>

<form method="POST" action="../controllers/auth.php">
    <input class="form-control mb-2" name="correo" placeholder="Correo">

    <input type="password" class="form-control mb-2" name="password" placeholder="Contraseña">

    <button class="btn btn-primary w-100">
        Ingresar
    </button>
</form>

</div>

<?php include "../includes/footer.php"; ?>