<?php
include("conexion.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$id_usu = $_POST["id_usu"];
$new_password = $_POST["new_password"];

// Verificar si los campos están vacíos
if (empty($id_usu) || empty($new_password)) {
    echo json_encode(["error" => true, "mensaje" => "Faltan datos"]);
    exit();
}

// NO encriptar la contraseña, se guarda tal cual
$sql = "UPDATE usuarios SET usu_pass = '$new_password' WHERE id_usu = '$id_usu'";
if (mysqli_query($conn, $sql)) {
    echo json_encode(["error" => false, "mensaje" => "Contrasenia actualizada correctamente"]);
} else {
    echo json_encode(["error" => true, "mensaje" => "Error al actualizar la contrasenia"]);
}

mysqli_close($conn);
?>
