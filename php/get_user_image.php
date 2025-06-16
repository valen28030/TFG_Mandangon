<?php
include("conexion.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

if (!isset($_GET["id_usu"])) {
    echo json_encode(["error" => true, "mensaje" => "ID de usuario no recibido"]);
    exit();
}

$id_usu = $_GET["id_usu"];

// Obtener la ruta de la imagen del usuario desde la base de datos
$sql = "SELECT usu_img FROM usuarios WHERE id_usu = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usu);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $imagePath);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

if ($imagePath) {
    // Construir la URL completa y agregar un parámetro de versión para forzar actualización
    $baseUrl = "http://localhost/mandangon/";
    $version = time();
    echo json_encode(["error" => false, "image_path" => $baseUrl . $imagePath . "?v=" . $version]);
} else {
    echo json_encode(["error" => true, "mensaje" => "No se encontró imagen"]);
}
?>
