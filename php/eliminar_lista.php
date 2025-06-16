<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Incluir el archivo de conexión
include('conexion.php');

// Obtener el id_list desde la solicitud POST
$id_list = intval($_POST['id_list']); // Convertir a entero

if ($id_list > 0) {
    // Consulta SQL para eliminar la lista
    $sql = "DELETE FROM lista_compra WHERE id_list = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_list);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Lista eliminada correctamente"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al eliminar la lista: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conn->error]);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "ID de lista no válido"]);
}
?>
