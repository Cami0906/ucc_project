<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado");
}

require_once 'Conexion.php'; // Asegúrate de que el archivo esté en la misma carpeta

// Obtener datos del formulario
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$contrasena = isset($_POST['password']) ? $_POST['password'] : '';


// Buscar el email en la base de datos
$sql = "SELECT E.Contraseña AS contrasena_hash
        FROM EMAILS_ESTUDIANTES EM
        INNER JOIN ESTUDIANTES E ON EM.Id_Email_Estudiante = E.Id_Email_Estudiante
        WHERE EM.Email = ?";
$stmt = $link->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();


if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    
    // Verificar la contraseña
    if (password_verify($contrasena, $usuario['contrasena_hash'])) {
    // Obtener el ID del estudiante
    $sql_id = "SELECT E.Id_Estudiante FROM ESTUDIANTES E 
               INNER JOIN EMAILS_ESTUDIANTES EM ON E.Id_Email_Estudiante = EM.Id_Email_Estudiante
               WHERE EM.Email = ?";
    $stmt_id = $link->prepare($sql_id);
    $stmt_id->bind_param("s", $email);
    $stmt_id->execute();
    $result_id = $stmt_id->get_result();
    
    if ($result_id->num_rows === 1) {
        $row = $result_id->fetch_assoc();
        $_SESSION["usuario"] = $email;
        $_SESSION["is_logged"] = true;
        $_SESSION["id_estudiante"] = $row['Id_Estudiante']; // Aquí guardamos el ID
        header("Location: ../Conexion/dashboard.php");
        exit;
    }
    $stmt_id->close();
} else {
        echo "<script>alert('Contraseña incorrecta'); history.back();</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); history.back();</script>";
}

$stmt->close();
$link->close();
?>
