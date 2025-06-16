<?php
include("conexion.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usu = isset($_POST["id_usu"]) ? intval($_POST["id_usu"]) : 0;

    if ($id_usu > 0) {
        // Primero eliminar reseñas relacionadas
        $sqlResenias = "DELETE FROM resenias WHERE res_usu = ?";
        $stmtResenias = $conn->prepare($sqlResenias);
        $stmtResenias->bind_param("i", $id_usu);
        $stmtResenias->execute();
        $stmtResenias->close();

        // Ahora sí, eliminar el usuario
        $sqlUsuario = "DELETE FROM usuarios WHERE id_usu = ?";
        $stmtUsuario = $conn->prepare($sqlUsuario);
        $stmtUsuario->bind_param("i", $id_usu);

        if ($stmtUsuario->execute()) {
            echo json_encode(["success" => true, "message" => "Cuenta eliminada correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al eliminar la cuenta"]);
        }
        $stmtUsuario->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID de usuario no válido"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}

$conn->close();
?>
