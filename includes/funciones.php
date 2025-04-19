<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}//-----------------------------------------------------------------------------------------------------

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}//-----------------------------------------------------------------------------------------------------

//Permite visualizar los archivos json de manera facil. Sin la orden head no se visualizan correctamente.
function verJSON($var) {
    
    header('Content-Type: application/json');
    return json_encode($var);
}//-----------------------------------------------------------------------------------------------------

//Funcion que revisa que el usuario está autentificado

function isAuth() : void {

    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}//-----------------------------------------------------------------------------------------------------

function esUltimo( string $actual, string $proximo) :bool {
    
    if( $actual !== $proximo) {
        return true;
    }

    return false; 
}//-----------------------------------------------------------------------------------------------------

function isAdmin(): void {
    if(!isset($_SESSION['admin'])) {
        header("Location: /");
    }
}//-----------------------------------------------------------------------------------------------------
