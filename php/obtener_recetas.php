<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('conexion.php');

// Verificar que se haya enviado el ID del usuario 
if (!isset($_GET['usuario_id']) || empty($_GET['usuario_id'])) {
    echo json_encode(["success" => false, "message" => "Falta el usuario_id"]);
    exit();
}

$usuario_id = $_GET['usuario_id'];

// Preparar la consulta para obtener las recetas del usuario
$query = $conn->prepare("SELECT * FROM recetas WHERE rec_usu = ?");
if (!$query) {
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta: " . $conn->error]);
    exit();
}

// Asumimos que usuario_id es un entero
$query->bind_param("i", $usuario_id);
$query->execute();
$resultado = $query->get_result();

// Crear un arreglo para almacenar las recetas
$recetas = array();
while ($row = $resultado->fetch_assoc()) {
    $recetas[] = $row;
}

// Devolver las recetas en formato JSON
echo json_encode($recetas);

$query->close();
$conn->close();
?>