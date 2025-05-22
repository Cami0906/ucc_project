<?php
session_start();
if (!isset($_SESSION["is_logged"]) || !isset($_SESSION["id_estudiante"])) {
    header("Location: index.php");
    exit;
}

include_once "Conexion.php";
$Id_Estudiante = $_SESSION['id_estudiante'];
if (!$Id_Estudiante) {
    die("Faltan parámetros necesarios");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripciones del Estudiante</title>
    <link rel="stylesheet" href="./CSS/vistaInscripciones.css">
</head>
<body>
<?php
$sql = "SELECT i.*, a.Nombre AS Nombre_Asignatura, 
               CONCAT(p.Nombre, ' ', p.Apellido) AS Profesor,
               esp.Lugar AS Aula,
               CONCAT(h.Dia_Semana, ' ', h.Hora_Inicio, '-', h.Hora_Salida) AS Horario,
               e.Nombre AS Nombre_Estudiante
        FROM inscripciones i
        JOIN clases c ON i.Id_Clase = c.Id_Clase
        JOIN asignaturas a ON c.Id_Asignatura = a.Id_Asignatura
        JOIN profesores p ON c.Id_Profesor = p.Id_Profesor
        JOIN espacio esp ON c.Id_Espacio = esp.Id_Espacio
        JOIN horarios h ON c.Id_Horario = h.Id_Horario
        JOIN estudiantes e ON i.Id_Estudiante = e.Id_Estudiante
        WHERE i.Id_Estudiante = ?";

$stmt = $link->prepare($sql);
if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $link->error);
}
$stmt->bind_param("i", $Id_Estudiante);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
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
        echo "<p>No se encontraron inscripciones para este estudiante.</p>";
    }
} else {
    echo "<p>Error al ver inscripciones: " . $stmt->error . "</p>";
}

$stmt->close();
$link->close();
?>

<!-- Botón de regresar -->
<div style="margin-top: 20px; text-align: center;">
  <button id="btnRegresar" style="
    background-color:rgb(36, 50, 118);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;">
    Regresar
  </button>
</div>

<script>
  document.getElementById("btnRegresar").addEventListener("click", function () {
    window.history.back();
  });
</script>
</body>
</html>
