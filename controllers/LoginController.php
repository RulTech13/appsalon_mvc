<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Models\Usuario;

class LoginController {

    public static function login(Router $router) {
        
        $alertas = [];
        

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                $usuario = Usuario::where('email' , $auth->email);
                if($usuario){
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin'); 
                        } else {
                             header('Location: /cita'); 
                        }
                    
                    }
                } else {
                    Usuario::setAlerta('error' , 'Usuario no registrado.');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/login' , ['alertas'=>$alertas]);
        
    }//--------------------------------------------------------------------------------- */

    public static function logout() {

       session_start();
       $_SESSION = [];
       header('Location: /');
    }//--------------------------------------------------------------------------------- */

    public static function olvide(Router $router) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] ==='POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('email' , $auth->email);
                if($usuario && $usuario->confirmado==="1") {
                    $usuario->crearToken();
                    $usuario->guardar();
                    $email = new Email($usuario->email , $usuario->nombre , $usuario->token);
                    $email->enviarInstrucciones();
                    $usuario->setAlerta('exito' , 'Revisa tu Email para Confirmar la Contraseña.');
                } else {
                    Usuario::setAlerta('error' , 'Usuario no registrado previamente');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password' , ['alertas'=>$alertas] );

    }//--------------------------------------------------------------------------------- */

    public static function recuperar(Router $router) {
        
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        $usuario = Usuario::where('token' , $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error' , 'Registro no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] ==='POST') {

            if($_POST['password'] === $_POST['password2']) {

                $password= new Usuario($_POST);
                $alertas = $password->validarPassword();
                if(empty($alertas)) {
                    
                    $usuario->password = null;//eliminar antiguo password
                    $usuario->password = $password->password;//guardar en el usuario la contraseña introducida en el form
                    $usuario->hashPassword();
                    $usuario->token = '';
                    $resultado = $usuario->guardar();
                    if($resultado) header('Location: /');
                }

            } else {
                $error = false;
                Usuario::setAlerta('error' , 'Las Contraseñas no Coinciden.');
            }
            
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password' ,  [ 'alertas' => $alertas ,
                                                        'error'=> $error]);

    }//--------------------------------------------------------------------------------- */

    public static function crear(Router $router) {

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']  ==='POST'){
           
            //llevar datos introducidos en el post a la memoria por medio de la variable usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                //verificar que este mail no esta registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {

                    //Hashear el password
                    $usuario->hashPassword();
                    //Crear token
                    $usuario->crearToken();
                    //Enviar Email
                    $email = new Email($usuario->email , $usuario->nombre , $usuario->token);
                    //Enviar mail de confirmacion
                    $email->enviarConfirmacion();
                    //guardar usuario
                    $resultado = $usuario->guardar();
                    
                    if($resultado) { 
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Renderizar la vista
        $router->render('auth/crear-cuenta' , [ 'usuario'=>$usuario ,
                                                'alertas'=>$alertas]);

    }//--------------------------------------------------------------------------------- */

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje');
    }//--------------------------------------------------------------------------------- */

    public static function confirmar(Router $router) {

        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token' , $token);
    
        if(empty($usuario)){
            //mostrar mensaje error
            Usuario::setAlerta('error' , 'Proceso de verificación no válido(token)');
        } else {
            //modificar a usuario confirmado campos confirmado y borrar token de la bd
            $usuario->confirmado = "1";
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito' , 'Cuenta Comprobada Correctamente.');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta' , ['alertas'=>$alertas]);

    }//--------------------------------------------------------------------------------- */

}

?>