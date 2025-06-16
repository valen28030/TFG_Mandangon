<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include('conexion.php');

$titulo = $_POST['rec_nom'];
$tipo = $_POST['rec_tipo_com'];
$ingredientes = $_POST['rec_ing'];
$instrucciones = $_POST['rec_desc'];
$tiempo = $_POST['rec_tmp'];
// Se espera que el ID del usuario se envíe en el campo "rec_usu"
$usuario = $_POST['rec_usu'];

// Manejo de la imagen
$directorioSubida = "uploads/"; // Carpeta donde se guardarán las imágenes
$nombreImagen = basename($_FILES["rec_img"]["name"]); // Nombre original del archivo
$rutaImagen = $directorioSubida . $nombreImagen; // Ruta completa

// Mover la imagen al servidor
if (move_uploaded_file($_FILES["rec_img"]["tmp_name"], $rutaImagen)) {
    // Insertar los datos en la base de datos, incluyendo el ID del usuario
    $query = "INSERT INTO recetas (rec_nom, rec_tipo_com, rec_img, rec_desc, rec_ing, rec_tmp, rec_usu) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssss", $titulo, $tipo, $rutaImagen, $instrucciones, $ingredientes, $tiempo, $usuario);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true, "message" => "Receta guardada correctamente"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar la receta: " . mysqli_stmt_error($stmt)]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Error al subir la imagen"]);
}

mysqli_close($conn);
?>
