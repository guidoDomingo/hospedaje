<?php

if(count($_POST)>0){

	$cliente = new PersonaData();
	$cliente->tipo_documento = $_POST["tipo_documento"];
	$cliente->documento = $_POST["documento"];
	$cliente->nombre = $_POST["nombre"];

	$razon_social="NULL";
  if($_POST["razon_social"]!=""){ $razon_social=$_POST["razon_social"];}

  $telefono="NULL";
  if($_POST["telefono"]!=""){ $telefono=$_POST["telefono"];}

  $direccion="NULL";
  if($_POST["direccion"]!=""){ $direccion=$_POST["direccion"];}

  $fecha_nac="";
  if($_POST["fecha_nac"]!=""){ $fecha_nac=$_POST["fecha_nac"];}
  


	$cliente->razon_social = $razon_social;
	$cliente->telefono = $telefono;
	$cliente->direccion = $direccion;
	$cliente->fecha_nac = $fecha_nac;
 
	$cliente->addCliente();

print "<script>window.location='index.php?view=cliente';</script>";


}
