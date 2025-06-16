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

if (!isset($_FILES["image"])) {
    echo json_encode(["error" => true, "mensaje" => "No se recibió ninguna imagen"]);
    exit();
}

$archivo = $_FILES["image"];

if ($archivo["error"] !== UPLOAD_ERR_OK) {
    echo json_encode(["error" => true, "mensaje" => "Error al subir el archivo. Código de error: " . $archivo["error"]]);
    exit();
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
if (!in_array($archivo['type'], $allowedTypes)) {
    echo json_encode(["error" => true, "mensaje" => "El tipo de archivo no es permitido"]);
    exit();
}

// Definir carpeta de subida y generar nombre único para evitar sobrescrituras
$directorioSubida = "uploads/";
$extension = pathinfo($archivo["name"], PATHINFO_EXTENSION);
$fileName = "user_{$id_usu}." . $extension;
$rutaImagen = $directorioSubida . $fileName;

if (!move_uploaded_file($archivo['tmp_name'], $rutaImagen)) {
    echo json_encode(["error" => true, "mensaje" => "Error al mover la imagen al directorio de destino"]);
    exit();
}

// Actualizar la base de datos con la ruta (relativa) de la imagen
$sql = "UPDATE usuarios SET usu_img = ? WHERE id_usu = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $rutaImagen, $id_usu);

if (mysqli_stmt_execute($stmt)) {
    // Construir la URL completa de la imagen y agregar un parámetro de versión para evitar caché
    $baseUrl = "http://localhost/mandangon/";
    $version = time();
    $imageUrl = $baseUrl . $rutaImagen . "?v=" . $version;
    echo json_encode(["error" => false, "mensaje" => "Imagen subida correctamente", "image_path" => $imageUrl]);
} else {
    echo json_encode(["error" => true, "mensaje" => "Error al guardar la imagen en la base de datos"]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
