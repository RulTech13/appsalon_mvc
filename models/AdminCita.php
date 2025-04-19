<?php

namespace Models;

class AdminCita extends ActiveRecord {

    //Los datos provienen de diferrentes tablas (query con join) pero la principal es citasservicios
    protected static $tabla = 'citasservicios';

    //Los ombres de las columnas son alias generados en la query sql
    protected static $columnasDB = ['id','hora','cliente','email','telefono','servicio','precio'];

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    function __construct() {

        $this->id = $args['id'] ?? null;
        $this->hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
    
}