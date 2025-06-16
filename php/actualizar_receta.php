<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include('conexion.php');

// Validar campos obligatorios
if (!isset($_POST['rec_nom_original']) || empty($_POST['rec_nom_original']) || !isset($_POST['rec_usu']) || empty($_POST['rec_usu'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
    exit();
}

$rec_nom_original = $_POST['rec_nom_original'];
$titulo = $_POST['rec_nom'];
$tipo = $_POST['rec_tipo_com'];
$ingredientes = $_POST['rec_ing'];
$instrucciones = $_POST['rec_desc'];
$tiempo = $_POST['rec_tmp'];
$usuario = $_POST['rec_usu'];

// Procesar la imagen (si se ha subido una nueva)
if (isset($_FILES['rec_img']) && $_FILES['rec_img']['error'] == 0) {
    $uploadDir = "uploads/";
    $nombreImagen = basename($_FILES["rec_img"]["name"]);
    $rutaImagen = $uploadDir . uniqid() . "_" . $nombreImagen;

    if (!move_uploaded_file($_FILES["rec_img"]["tmp_name"], $rutaImagen)) {
        echo json_encode(["success" => false, "message" => "Error al subir la imagen"]);
        exit();
    }
} else {
    // Si no se sube imagen, se mantiene la actual enviada por POST
    $rutaImagen = $_POST['rec_img'];
}

// Actualizar la receta asegurándose que pertenece al usuario
$query = $conn->prepare("UPDATE recetas 
    SET rec_nom = ?, rec_tipo_com = ?, rec_ing = ?, rec_desc = ?, rec_tmp = ?, rec_img = ? 
    WHERE rec_nom = ? AND rec_usu = ?");
if (!$query) {
    echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta: " . $conn->error]);
    exit();
}

$query->bind_param("sssssssi", $titulo, $tipo, $ingredientes, $instrucciones, $tiempo, $rutaImagen, $rec_nom_original, $usuario);

if ($query->execute()) {
    if ($query->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Receta actualizada correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se actualizó ningún registro"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar la receta: " . $query->error]);
}

$query->close();
$conn->close();
?>
