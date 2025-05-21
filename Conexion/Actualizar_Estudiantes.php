<?php
session_start();

// Verificar sesión más estrictamente
if (empty($_SESSION["is_logged"]) || empty($_SESSION["usuario"]) || empty($_SESSION["id_estudiante"])) {
    header("Location: index.php");
    exit;
}

require_once 'Conexion.php';

// Verificar conexión a la base de datos
if (!$link || $link->connect_error) {
    die("Error de conexión: " . ($link ? $link->connect_error : "No se pudo establecer conexión"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del estudiante directamente desde la sesión
    $id_estudiante = filter_var($_SESSION['id_estudiante'], FILTER_VALIDATE_INT);
    $nombre = trim($_POST['Nombre'] ?? '');
    $apellido = trim($_POST['Apellido'] ?? '');

    // Validaciones
    if (!$id_estudiante || $id_estudiante < 1) {
        die("ID de estudiante inválido");
    }

    if (empty($nombre) || empty($apellido)) {
        die("Nombre y apellido son campos obligatorios");
    }

    if (strlen($nombre) > 50 || strlen($apellido) > 50) {
        die("Nombre y apellido no deben exceder 50 caracteres");
    }

    try {
        // Verificar existencia del estudiante
        $sql_check = "SELECT 1 FROM ESTUDIANTES WHERE Id_Estudiante = ? LIMIT 1";
        $stmt_check = $link->prepare($sql_check);
        
        if (!$stmt_check) {
            throw new Exception("Error al preparar consulta: " . $link->error);
        }

        $stmt_check->bind_param("i", $id_estudiante);
        
        if (!$stmt_check->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt_check->error);
        }

        $stmt_check->store_result();
        
        if ($stmt_check->num_rows == 0) {
            throw new Exception("No existe estudiante con ID $id_estudiante");
        }
        $stmt_check->close();

        // Actualizar datos
        $sql = "UPDATE ESTUDIANTES SET Nombre = ?, Apellido = ? WHERE Id_Estudiante = ?";
        $stmt = $link->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error al preparar actualización: " . $link->error);
        }

        $stmt->bind_param("ssi", $nombre, $apellido, $id_estudiante);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "Datos actualizados correctamente";
            } else {
                echo "No se realizaron cambios (los datos pueden ser iguales)";
            }
        } else {
            throw new Exception("Error al actualizar: " . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        // Manejo centralizado de errores
        error_log("Error en actualización: " . $e->getMessage());
        die("Ocurrió un error. Por favor intente más tarde.");
    }
} else {
    header("Location: index.php");
    exit;
}

// Cerrar conexión si está abierta
if (isset($link) && $link) {
    $link->close();
}
?>
