<?php
 session_start();

// Puedes verificar si está logueado
if (!$_SESSION["is_logged"]) {
    session_destroy();
    header("Location: ../Conexion/index.php");
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
    <div class="box">Inscribir materias</div>
    <div class="box">Ver materias</div>
    <div class="box">Actualizar mis datos</div>
    <div class="box">Borrar inscripción</div>
  </main>
</body>
</html>
