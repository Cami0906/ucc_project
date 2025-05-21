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
        // Inicio de sesión exitoso
        // Aquí podrías guardar datos en sesión si lo deseas
        $_SESSION["usuario"] = $_POST['email'];
        $_SESSION["is_logged"] = true;
        session_start();
        header("Location: ../Conexion/dashboard.php");
    } else {
        echo "<script>alert('Contraseña incorrecta'); history.back();</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); history.back();</script>";
}

$stmt->close();
$link->close();
?>
