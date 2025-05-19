<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso denegado");
}

require_once 'Conexion.php';

// Iniciar transacción
$link->begin_transaction();

try {
    // Validar y sanitizar datos
    $nombre = filter_input(INPUT_POST, 'nombre');
    $apellido = filter_input(INPUT_POST, 'apellido');
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasena = $_POST['contrasena'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($fechaNacimiento) || empty($email) || empty($contrasena)) {
        throw new Exception("Todos los campos son obligatorios");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El formato del email no es válido");
    }

    if (strlen($contrasena) < 6) {
        throw new Exception("La contraseña debe tener al menos 6 caracteres");
    }

    // Verificar si el email ya existe
    $stmt = $link->prepare("SELECT Id_Email_Estudiante FROM EMAILS_ESTUDIANTES WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception("El email ya está registrado");
    }
    $stmt->close();

    // 1. Insertar en EMAILS_ESTUDIANTES
    $stmt = $link->prepare("INSERT INTO EMAILS_ESTUDIANTES (Email) VALUES (?)");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "Email registrado correctamente.<br>";
        
    }else {
        throw new Exception("Error al registrar email: " . $stmt->error); 
    }
    $idEmail = $stmt->insert_id;
    $stmt->close();

    // Hashear la contraseña
    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

    // 2. Insertar en ESTUDIANTES
    $stmt = $link->prepare("INSERT INTO ESTUDIANTES (Nombre, Apellido, Fecha_Nacimiento, Id_Email_Estudiante, Contrasena) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $nombre, $apellido, $fechaNacimiento, $idEmail, $contrasenaHash);

    if ($stmt->execute()) {

        echo "Estudiante registrado correctamente.<br>";
        
    }else {
        throw new Exception("Error al registrar estudiante: " . $stmt->error);
    }
    $stmt->close();

    // Confirmar transacción
    $link->commit();
    
    
    exit();
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $link->rollback();
    die("Error: " . $e->getMessage());
} finally {
    $link->close();
}
?>