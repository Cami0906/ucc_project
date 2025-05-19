<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
require_once 'Conexion.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

// Hashear la contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Preparar la sentencia SQL
$stmt = $conexion->prepare("INSERT INTO estudiantes (nombre, apellido, fecha_nacimiento, email, contrasena) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error al preparar la consulta: " . $link->error);
}

// Asociar parámetros
$stmt->bind_param("sssss", $nombre, $apellido, $fechaNacimiento, $email, $contrasenaHash);

// Ejecutar
if ($stmt->execute()) {
    echo " Estudiante registrado con éxito.";
} else {
    echo " Error al registrar estudiante: " . $stmt->error;
}

$stmt->close();
$link->close();
?>
