<?php
require_once 'Conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $id_estudiante = $_POST['Id_Estudiante'] ?? null;
    $nombre = $_POST['Nombre'] ?? null;
    $apellido = $_POST['Apellido'] ?? null;

    

    if ($id_estudiante && $nombre && $apellido) {
        try {
            $sql_check = "SELECT * FROM ESTUDIANTES WHERE Id_Estudiante = ?";
            $stmt_check = $link->prepare($sql_check);

            if ($stmt_check) {
                $stmt_check->bind_param("i", $id_estudiante);
                $stmt_check->execute();
                $result = $stmt_check->get_result();

                if ($result->num_rows == 0) {
                    echo "Error: No existe un estudiante con el ID proporcionado.<br>";
                } else {
                    $sql = "UPDATE ESTUDIANTES SET Nombre = ?, Apellido = ? WHERE Id_Estudiante = ?";
                    $stmt = $link->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("ssi", $nombre, $apellido, $id_estudiante);

                        if ($stmt->execute()) {
                            echo "Estudiante actualizado correctamente.<br>";
                        } else {
                            echo "Error al actualizar el estudiante: " . $link->error . "<br>";
                        }

                        $stmt->close();
                    } else {
                        echo "Error al preparar la consulta de actualización: " . $link->error . "<br>";
                    }
                }

                $stmt_check->close();
            } else {
                echo "Error al preparar la consulta de verificación: " . $link->error . "<br>";
            }
        } catch (Exception $e) {
            echo "Excepción: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Faltan datos en el formulario.<br>";
    }
}
?>
