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
    // Muestra el mensaje estilizado
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Resultado</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                
                background: linear-gradient(135deg, #4a90e2, #2f2934);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-container {
                text-align: center;
                background: #fff;
                padding: 20px 40px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #5cb85c;
                font-size: 24px;
                margin-bottom: 10px;
            }
            p {
                font-size: 16px;
                margin-bottom: 20px;
                color: #333;
            }
            a {
                text-decoration: none;
                color: #fff;
                background-color: #0275d8;
                padding: 10px 15px;
                border-radius: 5px;
                font-size: 14px;
            }
            a:hover {
                background-color: #025aa5;
            }
        </style>
    </head>
    <body>
        <div class='message-container'>
            <h1>¡Inscripción Borrada!</h1>
            <p>La inscripción ha sido borrada correctamente.</p>
            <a href='formularioDelete.html'>Volver</a>
        </div>
    </body>
    </html>";
} else {
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8d7da;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .message-container {
                text-align: center;
                background: #fff;
                padding: 20px 40px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                color: #721c24;
                background-color: #f8d7da;
            }
            h1 {
                font-size: 24px;
                margin-bottom: 10px;
            }
            p {
                font-size: 16px;
                margin-bottom: 20px;
            }
            a {
                text-decoration: none;
                color: #721c24;
                padding: 10px 15px;
                border-radius: 5px;
                border: 1px solid #721c24;
            }
            a:hover {
                background-color: #f5c6cb;
            }
        </style>
    </head>
    <body>
        <div class='message-container'>
            <h1>Error</h1>
            <p>No se pudo borrar la inscripción. Por favor, intente de nuevo.</p>
            <a href='formularioDelete.html'>Volver</a>
        </div>
    </body>
    </html>";
}

$stmt->close();
$link->close();
?>
