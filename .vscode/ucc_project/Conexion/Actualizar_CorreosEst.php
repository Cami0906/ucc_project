<?php
require_once 'Conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $id_email_estudiante = $_POST['Id_Email_Estudiante'] ?? null;
    $email = $_POST['Email'] ?? null;

    

    if ($id_email_estudiante && $email ) {
        try {
            $sql_check = "SELECT * FROM EMAILS_ESTUDIANTES WHERE Id_Email_Estudiante = ?";
            $stmt_check = $link->prepare($sql_check);

            if ($stmt_check) {
                $stmt_check->bind_param("i", $id_email_estudiante);
                $stmt_check->execute();
                $result = $stmt_check->get_result();

                if ($result->num_rows == 0) {
                    echo "Error: No existe un email con el ID proporcionado.<br>";
                } else {
                    $sql = "UPDATE EMAILS_ESTUDIANTES SET Email = ? WHERE Id_Email_Estudiante = ?";
                    $stmt = $link->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("si",  $email, $id_email_estudiante);

                        if ($stmt->execute()) {
                            echo "Correo actualizado correctamente.<br>";
                        } else {
                            echo "Error al actualizar el Correo: " . $link->error . "<br>";
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
