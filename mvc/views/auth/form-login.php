<?php
?>
<h1>Iniciar sesión</h1>

<p>Ingresá tus credenciales para iniciar sesión en el sitio.</p>

<form action="<?= url('login');?>" method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
</form>
