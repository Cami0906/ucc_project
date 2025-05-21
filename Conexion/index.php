<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Inicio de Sesión</title>
  <link rel="stylesheet" href="sttyles.css" />
</head>
<body>
  <main class="login-container" role="main" aria-label="Formulario de inicio de sesión">
    <h2>Iniciar Sesión</h2>
    <form action="login.php" method="POST">
      <label for="email">Correo electrónico</label>
      <input type="email" id="email" name="email" required />

      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required minlength="6" />

      <button type="submit">Iniciar Sesión</button>
    </form>
  </main>
</body>
</html>
