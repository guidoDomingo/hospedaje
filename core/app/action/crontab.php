<?php
// include "core/autoload.php";
// include "core/app/autoload.php";
date_default_timezone_set('America/Asuncion'); // Establecer la zona horaria a Nueva York

class Crontab
{

    public function verifica_fecha_salida()
    {

        try{
            // Conexión a la base de datos (asegúrate de tener tu propia configuración)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hospedaje1";

            $url_base = 'C:\xampp\htdocs';

            // Crea la conexión
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica la conexión
            if ($conn->connect_error) {
                die("Conexión a la base de datos fallida: " . $conn->connect_error);
            }

            // Consulta SQL para obtener la fecha_salida de tu tabla (ajusta el nombre de la tabla y la columna según tus necesidades)
            $sql = "select * from proceso p where p.estado = 0";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    $fecha_salida = $row["fecha_salida"];

                    $fecha_actual = date("Y-m-d H:i:s");

                    $marca_tiempo_bd = strtotime($fecha_salida);
                    $marca_tiempo_actual = strtotime($fecha_actual);


                    $cant_noche = $row["cant_noche"] + 1;

                    $id_proceso = $row["id"];

                    $id_habitacion = $row["id_habitacion"];

                    if ($marca_tiempo_actual > $marca_tiempo_bd) {

                        $mensaje = "La fecha actual es mayor que la fecha de salida." . $fecha_salida . PHP_EOL;
                        error_log($mensaje, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');

                        $fecha_salida = strtotime("+1 day", $fecha_salida);

                        // Convierte la nueva fecha de salida de nuevo al formato deseado (por ejemplo, Y-m-d)
                        $nueva_fecha_salida = date("Y-m-d 09:00:00", $fecha_salida);

                        error_log("la nueva fecha es: " . $nueva_fecha_salida . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                        error_log("la nueva cantidad noche es: " . $cant_noche . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');

                        $updateSQL = "UPDATE proceso SET id_tipo_pago = ?, estado = ?, pagado = ?  WHERE id = ?";

                        $updateSQLhabitacion = "UPDATE habitacion SET estado = ?  WHERE id = ?";

                        // $habitacion = HabitacionData::getById($id_habitacion);
                        // $habitacion->estado = 3;
                        // $habitacion->updateEstado();

                        // Crear una sentencia preparada
                        if ($stmt = $conn->prepare($updateSQL)) {
                            error_log("entro en el if: " . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                            // Vincula los parámetros
                            $id_tipo_pago = 1; // Reemplaza con el valor real
                            $estado_pro = 1; // Reemplaza con el valor real
                            $pagado = 1; // Reemplaza con el valor real
                            $estado_ha = 3;

                            $stmt->bind_param("iiii", $id_tipo_pago, $estado_pro, $pagado, $id_proceso);

                            // Ejecuta la sentencia preparada
                            if ($stmt->execute()) {

                                $stmt1 = $conn->prepare($updateSQLhabitacion);
                                $stmt1->bind_param("ii", $estado_ha,$id_habitacion);
                                $stmt1->execute();      

                                error_log("Se actualizo correctamente: " . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                            } else {
                                error_log("No se pudo actualizar la tabla proceso: " . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                            }

                            error_log("paso de largo: " . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                        } else {
                            error_log("Error en la preparación de la consulta: " . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                        }
                    } else {
                        error_log("La fecha actual es menor o igual que la fecha de salida." . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
                    }
                }
            } else {
                error_log("No se encontraron registros en la tabla." . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
            }

            // Cierra la conexión a la base de datos
            $conn->close();
        }catch (Exception $e) {
            error_log("Error: " . $e->getMessage() . PHP_EOL, 3, $url_base.'\hospedaje\logs\mi_aplicacion.log.txt');
            //echo "Error: " . $e->getMessage();
        }
    }
}
