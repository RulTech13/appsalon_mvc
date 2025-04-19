<?php

namespace Controllers;

use MVC\Router;
use Models\Cita;
use Models\AdminCita;

class AdminController {

    public static function index(Router $router) {

        session_start();

        isAdmin();

        //selecciona la fecha pasada por parametro del buscador.js
        //en caso de no tener parametro utiliza la del sistema
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        //formatear fecha para poder comprobarla
        $fechas = explode('-' , $fecha);
        //explode retorna array con dia, año, mes
        //checkdate comprueba si es fecha valida y retorna boolean
        if( !checkdate($fechas[1] , $fechas[2] , $fechas[0])){
            header("Location: /404");
        }

        //Consultar la BD
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '{$fecha}' ";

        $citas = AdminCita::SQL($consulta);
        
        $router->render('admin/index' , [   'nombre' => $_SESSION['nombre'],
                                            'citas'=> $citas,
                                            'fecha'=> $fecha
                                        ]);
    }//--------------------------------------------------------------------------------

}