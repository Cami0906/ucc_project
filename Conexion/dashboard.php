<?php
 session_start();

// Puedes verificar si está logueado
if (empty($_SESSION["is_logged"]) || empty($_SESSION["usuario"]) || empty($_SESSION["id_estudiante"])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Minimalista</title>
  <link rel="stylesheet" href="dasboard.css" />
</head>
<body>
  <nav>
    <div>Bienvenido: <?php echo htmlspecialchars($_SESSION["usuario"]); ?></div>
    <div><a >Cerrar sesión</a></div>
  </nav>
  <main class="container">
    <a class="box" href="CreateInscripciones.php">Inscribir Materias</a>
    <a class="box" href="View_Inscripcion.php">Ver Materias</a>
    <a class="box" href="Update_estudiantes.html">Actualizar Datos</a>
    <a class="box" href="FormularioDelete.html">Borrar inscripciones</a>
  </main>
</body>
</html>
