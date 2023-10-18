<?php
$session_id = session_id();
ini_set('date.timezone', 'America/Asuncion');

if (count($_POST) > 0) {

    //return var_dump($_POST["id_habitacion"]);

    $habitacion = HabitacionData::getById($_POST["id_habitacion"]);
    $habitacion->estado = 1;
    $habitacion->updateEstado();


    $proceso = ProcesoData::getById($_POST["id_operacion"]);
    $f = $proceso->updateSalida1();


    print "<script>window.location='index.php?view=recepcion';</script>";
} else {
    print "<script>window.location='index.php?view=recepcion';</script>";
}
