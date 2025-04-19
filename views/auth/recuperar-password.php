<h1 class="nombre-pagina">Confirmar Contraseña</h1>
<p class="descripcion-pagina">Escribe el nuevo Password a continuación.</p>

<?php        
        @include_once __DIR__ . "/../templates/alertas.php";
?>

<?php
    if($error) return;
?>

<!-- no poner etiqueta action=""  ya que recibimos el token por parametro en la direccion y este action nos vuelve a redireccionar quitando el token-->
<form action="" class="formulario" method="POST">

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Tu Nuevo Password">
    </div>

    <div class="campo">
        <label for="password2">Confirma Password</label>
        <input type="password" name="password2" id="password2" placeholder="Confirma Tu Password">
    </div>

    <input type="submit" class="boton" value="Guardar Nuevo Password">

</form>

<div class="acciones">
        <a href="/">¿Ya tienes una Cuenta? Inicia Sesión</a>
        <a href="/crear-cuenta">Crear Nueva Cuenta</a>
</div>