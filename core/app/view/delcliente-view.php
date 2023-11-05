<?php

$proceso = ProcesoData::getProcesoCliente($_GET["id"]);

if($proceso){
    session_start(); // Inicia la sesión si no está iniciada.

    // Coloca el mensaje de error en una variable de sesión.
    $_SESSION['error_message'] = 'El cliente seleccionado tiene habitacion ocupada, no se puede eliminar todavia.';
    
    // Redirige al usuario a la página anterior o a cualquier otra página.
    Core::redir("./index.php?view=cliente");// Reemplaza 'pagina_anterior.php' con la URL correcta.
    exit;  
}

$cliente = ClienteData::getById1($_GET["id"]);
$cliente->del1();

Core::redir("./index.php?view=cliente");
