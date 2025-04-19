<?php

namespace Controllers;

use Models\Cita;
use Models\CitaServicio;
use Models\Servicio;

class APIController {

    public static function index() {
        
        $servicio = Servicio::all();
        //funcion en includes/funciones.php
        echo verJSON($servicio);
    }//--------------------------------------------------------------------------------

    public static function guardar() {

        //Almacena la cita(tabla citas) y retorna el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //Almacena citaServicio(tabla citasservicios)

        //Tenemos un string "1,3,4"
        //Toma como separador la coma y coge los elementos separados por "," y los pasa a un arreglo
        $idServicios = explode("," , $_POST['servicios']);

        //Almacena por cada registro, id de la cita, id del servicos escojido
        foreach($idServicios as $idServicio) {
            $args = [ 
                    'citaId' => $id , 
                    'servicioId' => $idServicio
                    ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        //funcion en includes/funciones.php
        echo verJSON(['resultado' => $resultado]);
    }//--------------------------------------------------------------------------------

    public static function eliminar() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            //instanciar la cita segun el id y eliminarla
            $cita = Cita::find($id);
            $cita->eliminar();
            //redirecciona a la pagina de la que proveniamos
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }//--------------------------------------------------------------------------------
}

?>