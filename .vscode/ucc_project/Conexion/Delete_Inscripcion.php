<?php
// delete_inscripcion.php

// Incluye el archivo de conexión
include_once "Conexion.php";


// Obtiene los parámetros POST
$Id_Estudiante = $_POST['Id_Estudiante'] ?? null;
$Id_Clase = $_POST['Id_Clase'] ?? null;

if (!$Id_Estudiante || !$Id_Clase) {
    die("Faltan parámetros necesarios");
}

// Usa $link desde Conexion.php para hacer la consulta
$sql = "DELETE FROM inscripciones WHERE Id_Estudiante = ? AND Id_Clase = ?";

$stmt = $link->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $link->error);
}

$stmt->bind_param("ii", $Id_Estudiante, $Id_Clase);

if ($stmt->execute()) {
    echo "Inscripción borrada correctamente.";
} else {
    echo "Error al borrar inscripción: " . $stmt->error;
}

$stmt->close();
$link->close();
?>
