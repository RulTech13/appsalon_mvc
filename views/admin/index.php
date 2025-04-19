<?php        
        @include_once __DIR__ . "/../templates/barra.php";
?>

<h1 class="nombre-pagina">Panel de administración</h1>
<p> SuperUsuario: <?php echo $nombre ?> - Permiso: administrador</p>

<h2>Buscar Citas por Fecha</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0 ) {
        echo "<br><h2>NO hay Citas para el Dia Seleccionado</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php 
            $idCita = 0;
            foreach($citas as $key => $cita){ 

                //Agrupa las citas con el mismo id
                if($idCita !== $cita->id) {
                    $total = 0;
        ?>
                <li>
                    <h3>Cliente</h3>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p><span><?php echo $cita->cliente; ?></p>
                    <p>Email: <span><?php echo $cita->email; ?></p>
                    <p>Telf: <span><?php echo $cita->telefono; ?></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></p>
                    <h3>Servicios</h3>
                    <?php $idCita = $cita->id; 
                } //fin if ?>

                <?php $total += $cita->precio; ?>
                <p class="servicio"><?php echo $cita->servicio . " - " . $cita->precio . "$"; ?></p>

                <?php
                    $actual = $cita->id;
                    $proximo = $citas[$key +1]->id ?? 0; 

                    if(esUltimo($actual , $proximo)) {
                    ?>
                        <p>Total: <span><?php echo $total ?>$ </span></p>

                        <!-- Creacion del API -->
                        <!-- llama a la pagina /api/eliminar que tiene enlazada la funcion Apicontroller::eliminar -->
                        <form action="/api/eliminar" method="POST">
                            <!-- campo oculto donde guardo el id -->
                            <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                            <!-- boton de submit -->
                            <input type="submit" class="boton-eliminar" value="❌ Eliminar">
                        </form>

                    <?php
                    }
                ?>
                
                <?php } //fin foreach ?>
                </li>
            
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>";
?>