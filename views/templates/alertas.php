<?php

    //TENGO ACCESO A LA VARIABLE ALERTAS PORQUE INCLUYO ESTE TEMLATE EN LA VISTA QUE CREO ALERTAS
    // alerta es arreglo asociative del tipo alert['error']['mensajes]
    foreach($alertas as $key=>$mensajes):
        foreach($mensajes as $mensaje):
?>
    <!-- crear un div con clase alerta y clase que incluye la &key( error o exito) y muestra el mensaje creado -->
    <div class="alerta <?php echo $key;?>">
        <?php echo $mensaje; ?>
    </div>

<?php
        endforeach;
    endforeach;

?>