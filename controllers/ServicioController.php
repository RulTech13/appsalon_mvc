<?php

namespace Controllers;

use MVC\Router;
use Models\Servicio;

class ServicioController {

    public static function index(Router $router) {

        session_start();

        // includes/funciones/isAdmin()
        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index' , [   'nombre' => $_SESSION['nombre'],
                                                'servicios' => $servicios
        ]);

    }//-------------------------------------------------------------------------------------------------

    public static function crear(Router $router) {
        
        session_start();

        // includes/funciones/isAdmin()
        isAdmin();

        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //pasa los datos del post a la variable servicio
            $servicio->sincronizar($_POST);
            //comprobar los campos rellenados
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/crear' , [   'nombre' => $_SESSION['nombre'],
                                                'servicio' => $servicio,
                                                'alertas' => $alertas
        ]);
    }//-------------------------------------------------------------------------------------------------

    public static function actualizar(Router $router) {
        
        session_start();

        // includes/funciones/isAdmin()
        isAdmin();

        $alertas= [];
        $id = is_numeric($_GET['id']);
        if(!$id) return;
        $servicio = Servicio::find($_GET['id']);
        
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('servicios/actualizar' , [  'nombre' => $_SESSION['nombre'],
                                                    'alertas'=> $alertas,
                                                    'servicio'=> $servicio

        ]);
    }//-------------------------------------------------------------------------------------------------

    //No colocamos Router $router como parametro de la funcion eliminar porque no hay que redireccionar
    public static function eliminar() {
        
        session_start();

        // includes/funciones/isAdmin()
        isAdmin();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            //No sanitizo el id porque proviene del input oculto id del index.php en views/servicios
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }

    }//-------------------------------------------------------------------------------------------------

}
