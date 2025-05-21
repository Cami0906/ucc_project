<?php
// listar_clases_con_inscripcion.php
include_once "Conexion.php";

// Procesar inscripción si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_clase'])) {
    session_start();
    if (!isset($_SESSION['id_estudiante'])) {
        die("Debe iniciar sesión para inscribirse");
    }

    $id_clase = $_POST['id_clase'];
    $id_estudiante = $_SESSION['id_estudiante'];
    $fecha_actual = date('Y-m-d');

    // Verificar si ya está inscrito
    $check_sql = "SELECT * FROM INSCRIPCIONES WHERE Id_Clase = ? AND Id_Estudiante = ?";
    $check_stmt = $link->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_clase, $id_estudiante);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Ya estás inscrito en esta clase');</script>";
    } else {
        // Insertar nueva inscripción
        $insert_sql = "INSERT INTO INSCRIPCIONES (Id_Clase, Id_Estudiante, Fecha_Inscripcion) VALUES (?, ?, ?)";
        $insert_stmt = $link->prepare($insert_sql);
        $insert_stmt->bind_param("iis", $id_clase, $id_estudiante, $fecha_actual);
        
        if ($insert_stmt->execute()) {
            echo "<script>alert('Inscripción exitosa');</script>";
        } else {
            echo "<script>alert('Error al inscribirse: " . addslashes($insert_stmt->error) . "');</script>";
        }
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// Consulta para listar clases
$sql = "SELECT 
        c.Id_Clase,
        a.Nombre AS Nombre_Asignatura,
        CONCAT(p.Nombre, ' ', p.Apellido) AS Nombre_Profesor,
        e.Lugar AS Espacio,
        CONCAT(h.Dia_Semana, ' ', h.Hora_Inicio, ' a ', h.Hora_Salida) AS Horario
        FROM clases c
        JOIN asignaturas a ON c.Id_Asignatura = a.Id_Asignatura
        JOIN profesores p ON c.Id_Profesor = p.Id_Profesor
        JOIN espacio e ON c.Id_Espacio = e.Id_Espacio
        JOIN horarios h ON c.Id_Horario = h.Id_Horario
        ORDER BY h.Dia_Semana, h.Hora_Inicio";

$stmt = $link->prepare($sql);

if ($stmt === false) {
    die("Error en la preparación de la consulta: " . $link->error);
}

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clases</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn-inscribir {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-inscribir:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Listado de Clases Disponibles</h2>';

if ($stmt->execute()) {
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo '<form method="post" action="">
              <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asignatura</th>
                        <th>Profesor</th>
                        <th>Espacio</th>
                        <th>Horario</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>';
        
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>'.$row['Id_Clase'].'</td>
                    <td>'.$row['Nombre_Asignatura'].'</td>
                    <td>'.$row['Nombre_Profesor'].'</td>
                    <td>'.$row['Espacio'].'</td>
                    <td>'.$row['Horario'].'</td>
                    <td>
                        <button type="submit" name="id_clase" value="'.$row['Id_Clase'].'" class="btn-inscribir">
                            Inscribirse
                        </button>
                    </td>
                  </tr>';
        }
        
        echo '</tbody></table></form>';
    } else {
        echo "<p>No se encontraron clases disponibles.</p>";
    }
} else {
    echo "<p>Error al obtener las clases: " . $stmt->error . "</p>";
}

echo '</body></html>';

$stmt->close();
$link->close();
?>