<?php
include("conexion.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = isset($_POST["rec_nom"]) ? $_POST["rec_nom"] : "";
    $usuario = isset($_POST["rec_usu"]) ? intval($_POST["rec_usu"]) : 0;

    if (empty($nombre) || $usuario <= 0) {
        echo json_encode(["success" => false, "message" => "Nombre de receta o ID de usuario no válidos"]);
        exit();
    }

    // Verificar si la receta existe y pertenece al usuario
    $sql = "SELECT * FROM recetas WHERE rec_nom = ? AND rec_usu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombre, $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "La receta no existe o no pertenece al usuario"]);
        exit();
    }

    // Proceder a eliminar la receta
    $sql = "DELETE FROM recetas WHERE rec_nom = ? AND rec_usu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nombre, $usuario);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Receta eliminada correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "No se eliminó la receta"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar la receta"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

$conn->close();
