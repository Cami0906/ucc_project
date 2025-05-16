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
