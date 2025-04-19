
<?php        
        @include_once __DIR__ . "/../templates/barra.php";
?>

<!-- Variable nombre pasada por parametro desde el render de CitaController -->
<h1 class="nombre-pagina">Creador de Citas</h1>
<p class="descripcion-pagina"><?php echo $nombre ?? '' ?>, elige tus Servicios y Rellena tus Datos.</p>
<br>

<div id="app">

    <nav class="tabs">
        <!-- La etiqueta data-  crea nuestro propio atributo que puede ser tratado en js por ejemplo. data-paso="1"  equivale a paso="1" -->
        <!-- ver archivo app.js donde damos funcionalidad a los tabs usando este atributo -->
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div class="seccion mostrar" id="paso-1">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus Servicios a continuación.</p>
        <!-- Donde se inyecta el listado de servicios desde la api -->
        <div class="listado-servicios" id="servicios"></div>
    </div>

    <div class="seccion" id="paso-2">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Rellena tus Datos y Fecha de tu Cita.</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input id="nombre" type="text" placeholder="Tu Nombre" value="<?php echo $nombre;?>" disabled>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <!-- strtotime('+1 day'): permite añadirle un dia a la fecha indicada-->
                <input id="fecha" type="date" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input id="hora" type="time">
            </div>

            <input type="hidden" id="id" value="<?php echo $id; ?> ">

        </form>
    </div>

    <div class="seccion contenido-resumen" id="paso-3">
        <h2>Resumen</h2>
        <p>Verifica la Cita.</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">◄ Anterior</button>
        <button id="siguiente" class="boton">Siguiente ►</button>
    </div>

</div>

<?php
    
    //Creacion de variable script donde cargo el js solo para la pagina de cita.php y se carga en layout.php
    //Script de sweet alert para la pagina de citas/index
    $script = "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script src='build/js/app.js'></script>
            ";
?>