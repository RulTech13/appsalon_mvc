<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Actualizar Servicios</p>

<?php
    include_once __DIR__ . '/../templates/alertas.php';
?>

<!-- action="/servicios/actualizar" no se aplica en este caso para respetar el parametro enviado en la url-->
<form method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" class="boton" value="✔ Actualizar Servicio">
    
</form>