<?php

class Crontab {

    public function verifica_fecha_salida() {
 
    // Conexión a la base de datos (asegúrate de tener tu propia configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospedaje1";

    // Crea la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión a la base de datos fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener la fecha_salida de tu tabla (ajusta el nombre de la tabla y la columna según tus necesidades)
    $sql = "select * from proceso p where DATE(p.fecha_salida) = CURRENT_DATE and p.estado = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $fecha_salida = strtotime($row["fecha_salida"]);

            $fecha_actual = time();

            $cant_noche = $row["cant_noche"] + 1;

            $id_proceso = $row["id"];

            if ($fecha_actual > $fecha_salida) {

                $mensaje = "La fecha actual es mayor que la fecha de salida." . PHP_EOL;
                error_log($mensaje, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');

                $fecha_salida = strtotime("+1 day", $fecha_salida);

                // Convierte la nueva fecha de salida de nuevo al formato deseado (por ejemplo, Y-m-d)
                $nueva_fecha_salida = date("Y-m-d 09:00:00", $fecha_salida);

                error_log("la nueva fecha es: ". $nueva_fecha_salida . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');
                error_log("la nueva cantidad noche es: ". $cant_noche . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');

                $updateSQL = "UPDATE proceso SET cant_noche = ?, fecha_salida = ? WHERE id = ?";

                // Crear una sentencia preparada
                if ($stmt = $conn->prepare($updateSQL)) {
    
                    // Vincula los parámetros
                    $stmt->bind_param("sss", $cant_noche, $nueva_fecha_salida, $id_proceso);

                    // Ejecuta la sentencia preparada
                    if ($stmt->execute()) {
                        error_log("Se actualizo correctamente: " . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');
                    } else {
                        error_log("No se pudo actualizar la tabla proceso: " . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');
                    }

                } else {
                    error_log("Error en la preparación de la consulta: " . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');

                }

            } else {
                error_log("La fecha actual es menor o igual que la fecha de salida." . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');
            }
        }
    } else {
        error_log("No se encontraron registros en la tabla." . PHP_EOL, 3, 'C:\xampp_dos\htdocs\hospedaje\logs\mi_aplicacion.log.txt');
    }

    // Cierra la conexión a la base de datos
    $conn->close();


  }

}

?>
