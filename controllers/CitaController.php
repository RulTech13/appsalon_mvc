<?php

namespace Controllers;

use MVC\Router;


class CitaController {
    
    public static function index(Router $router) {

        session_start();//inicio sesion y escojo el nombre del usuario ya creados en el login

        // includes/funciones.php
        isAuth();

        $router->render('cita/index' , [  'nombre'=>$_SESSION['nombre'],
                                            'id' =>$_SESSION['id'] 
                                                                        ]);
    }//------------------------------------------------------------------------------------

}

?>