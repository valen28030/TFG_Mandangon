<?php
include("conexion.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if (!isset($_POST["id_usu"])) {
    echo json_encode(["error" => true, "mensaje" => "ID de usuario no recibido"]);
    exit();
}

$id_usu = $_POST["id_usu"];

// Obtener la ruta de la imagen antes de eliminarla
$sql_select = "SELECT usu_img FROM usuarios WHERE id_usu = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id_usu);
mysqli_stmt_execute($stmt_select);
mysqli_stmt_bind_result($stmt_select, $imagePath);
mysqli_stmt_fetch($stmt_select);
mysqli_stmt_close($stmt_select);

// Verificar si existe un archivo físico y eliminarlo
if ($imagePath && file_exists($imagePath)) {
    unlink($imagePath); // Elimina la imagen del servidor
}

// Eliminar la referencia de la imagen en la BD
$sql = "UPDATE usuarios SET usu_img = NULL WHERE id_usu = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_usu);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["error" => false, "mensaje" => "Imagen eliminada correctamente"]);
} else {
    echo json_encode(["error" => true, "mensaje" => "Error al eliminar la imagen de la BD"]);
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($conn);
?>
