<?php
// View_Inscripcion.php
session_start();
if (!isset($_SESSION["is_logged"]) || !isset($_SESSION["id_estudiante"])) {
    header("Location: index.php"); // Redirige si no hay sesión
    exit;
}
// Incluye el archivo de conexión
include_once "Conexion.php";

// Obtiene los parámetros POST
$Id_Estudiante = $_SESSION['id_estudiante'];

if (!$Id_Estudiante) {
    die("Faltan parámetros necesarios");
}

// Consulta mejorada con JOIN para obtener más información
$sql = "SELECT i.*, a.Nombre AS Nombre_Asignatura, 
               CONCAT(p.Nombre, ' ', p.Apellido) AS Profesor,
               esp.Lugar AS Aula,
               CONCAT(h.Dia_Semana, ' ', h.Hora_Inicio, '-', h.Hora_Salida) AS Horario,
               e.Nombre AS Nombre_Estudiante
        FROM inscripciones i
        JOIN clases c 
        ON i.Id_Clase = c.Id_Clase
        JOIN asignaturas a 
        ON c.Id_Asignatura = a.Id_Asignatura
        JOIN profesores p 
        ON c.Id_Profesor = p.Id_Profesor
        JOIN espacio esp 
        ON c.Id_Espacio = esp.Id_Espacio
        JOIN horarios h 
        ON c.Id_Horario = h.Id_Horario
        JOIN estudiantes e
        ON i.Id_Estudiante = e.Id_Estudiante

        WHERE i.Id_Estudiante = ?";

$stmt = $link->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $link->error);
}

$stmt->bind_param("i", $Id_Estudiante);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo '<link rel="stylesheet" href="./CSS/vistaInscripciones.css">';
        // Crear tabla HTML para mostrar los resultados
        echo '<table border="1" cellpadding="8" cellspacing="0" style="width:100%">
                <thead>
                    <tr>
                        <th>ID Inscripción</th>
                        <th>Asignatura</th>
                        <th>Profesor</th>
                        <th>Aula</th>
                        <th>Horario</th>
                        <th>Fecha Inscripción</th>
                        <th>Nombre Estudiante</th>
                        
                    </tr>
                </thead>
                <tbody>';
        
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>'.$row['Id_Inscripcion'].'</td>
                    <td>'.$row['Nombre_Asignatura'].'</td>
                    <td>'.$row['Profesor'].'</td>
                    <td>'.$row['Aula'].'</td>
                    <td>'.$row['Horario'].'</td>
                    <td>'.$row['Fecha_Inscripcion'].'</td>
                    <td>'.$row['Nombre_Estudiante'].'</td>
                    
                  </tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo "No se encontraron inscripciones para este estudiante.";
    }
} else {
    echo "Error al ver Inscripciones: " . $stmt->error;
}

$stmt->close();
$link->close();
?>