<?php 
namespace Models;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id' , 'nombre' , 'apellido' , 'email' , 'password' , 'telefono' , 'admin' , 'confirmado' , 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct( $args=[]) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }//------------------------------------------------------------------------------------------------------------

    //VALIDACION DE CAMPOS INTRODUCIDOS EN EL FORMULARIO CREAR-CUENTA
    public function validarNuevaCuenta() {
        if(!$this->nombre) { self::$alertas['error'] []= 'El nombre es obligatorio.'; }
        if(!$this->apellido) { self::$alertas['error'] []= 'El apellido es obligatorio.'; }
        if(!$this->telefono) { self::$alertas['error'] []= 'El telefono es obligatorio.'; }
        if(!$this->email) { self::$alertas['error'] []= 'El email es obligatorio.'; }
        if(!$this->password) { self::$alertas['error'] []= 'El password es obligatorio.'; }
        if( strlen($this->password) > 0 && strlen($this->password) < 6 ) { self::$alertas['error'] []= 'El password debe tener mínimo 6 dígitos.'; }

        return self::$alertas;
    }//------------------------------------------------------------------------------------------------------------

    //VALIDACION DE CAMPOS INTRODUCIDOS EN EL FORMULARIO LOGIN
    public function validarLogin() {
        if(!$this->email) { self::$alertas['error'] []= 'El Email es obligatorio.'; }
        if(!$this->password) { self::$alertas['error'] []= 'El password es obligatorio.'; }
        if( strlen($this->password) > 0 && strlen($this->password) < 6 ) { self::$alertas['error'] []= 'El password debe tener mínimo 6 dígitos.'; }

        return self::$alertas;
    }//------------------------------------------------------------------------------------------------------------

    //VALIDAR EL MAIL SI ESTA DADO DE ALTA EN LA BD Y CONFIRMADO
    public function validarEmail() {

        if(!$this->email) { self::$alertas['error'] []= 'El Email es obligatorio.'; }
        return self::$alertas;
    }//------------------------------------------------------------------------------------------------------------

    //VALIDAR EL PASSWORD 
    public function validarPassword() {

        if(!$this->password) { self::$alertas['error'] []= 'La Contraseña es obligatoria.'; }
        if( strlen($this->password) < 6 ) { self::$alertas['error'] []= 'La Contraseña debe tener mínimo 6 dígitos.'; }
        return self::$alertas;
    }//------------------------------------------------------------------------------------------------------------

    public function existeUsuario() {

        $query = "SELECT * FROM " . self::$tabla . " WHERE email='" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);
        if($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario está Registrado';
        }
        return $resultado;
    }//------------------------------------------------------------------------------------------------------------

    public function hashPassword() {

        $this->password = password_hash( $this->password , PASSWORD_BCRYPT);
    }//------------------------------------------------------------------------------------------------------------

    public function crearToken() {

        $this->token = uniqid();
    }//------------------------------------------------------------------------------------------------------------

    public function comprobarPasswordAndVerificado($password) {

        //compara el password obtenido del formulario con el post, contra el password guardado en la clase
        $resultado = password_verify($password , $this->password);

        if(!$resultado || !$this->confirmado) {
            self::setAlerta('error' , 'Revisar Datos de Inicio.');
        } else {
            return true;
        }
        
    }//------------------------------------------------------------------------------------------------------------
}
?>