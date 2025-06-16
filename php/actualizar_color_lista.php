<?php
// actualizar_color_lista.php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Incluir el archivo de conexión
include('conexion.php');

// Obtener los datos enviados en la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos necesarios están presentes
if (isset($data['id_list']) && isset($data['list_color'])) {
    $idLista = $data['id_list'];
    $nuevoColor = $data['list_color'];

    // Actualizar el color en la base de datos
    $sql = "UPDATE lista_compra SET list_color = '$nuevoColor' WHERE id_list = $idLista";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
}

// Cerrar la conexión
$conn->close();
?>
