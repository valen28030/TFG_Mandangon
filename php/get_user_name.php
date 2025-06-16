<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Obtener el usuario ID desde los parámetros GET
$usuario_id = $_GET['usuario_id']; 

// Consulta para obtener el nombre de usuario
$sql_usuario = "SELECT usu_nombre FROM usuarios WHERE id_usu = ?";
if ($stmt = $conn->prepare($sql_usuario)) {
    $stmt->bind_param("i", $usuario_id);  // Usamos el ID del usuario
    $stmt->execute();
    $resultado_usuario = $stmt->get_result();
    
    if ($resultado_usuario->num_rows == 0) {
        die(json_encode(['error' => true, 'mensaje' => 'No se encontró el usuario']));
    }

    $usuario = $resultado_usuario->fetch_assoc();
    
    // Devuelve el nombre de usuario en formato JSON
    echo json_encode([
        'error' => false,
        'usu_nombre' => $usuario['usu_nombre']
    ]);
} else {
    die(json_encode(['error' => true, 'mensaje' => 'Error en la consulta de usuario']));
}

// Cerrar conexión
$conn->close();
?>
