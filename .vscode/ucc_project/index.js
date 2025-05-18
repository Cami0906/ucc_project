


/// Delete method
function borrarInscripcion(idEstudiante, idClase) {
  fetch('delete_inscripcion.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `Id_Estudiante=${idEstudiante}&Id_Clase=${idClase}`
  })
  .then(response => response.text())
  .then(data => alert(data))
  .catch(error => console.error('Error:', error));
}

borrarInscripcion(1, 1); // borra la inscripción del estudiante 1 en la clase 1

///create method
document.getElementById("registroEstudiante").addEventListener("submit", function (e) {
  e.preventDefault(); // Evita recarga

  const nombre = document.getElementById("nombre").value;
  const apellido = document.getElementById("apellido").value;
  const fechaNacimiento = document.getElementById("fechaNacimiento").value;
  const email = document.getElementById("email").value;
  const contrasena = document.getElementById("contrasena").value;

  const formData = new FormData();
  formData.append("nombre", nombre);
  formData.append("apellido", apellido);
  formData.append("fechaNacimiento", fechaNacimiento);
  formData.append("email", email);
  formData.append("contrasena", contrasena);

  fetch("Create_Inscripcion.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      document.getElementById("resultado").innerHTML = data;
    })
    .catch(error => {
      document.getElementById("resultado").innerHTML = "Error: " + error;
    });
});