<?php
include("conexion.php");

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$nombre = trim($_POST["nombre"]); // Eliminar espacios al principio y al final
$pass = $_POST["contrasenia"];
$email = trim($_POST["email"]); // Eliminar espacios al principio y al final
$pregunta = $_POST["pregunta"];  // Recibir la pregunta de seguridad
$respuesta = trim($_POST["respuesta"]);  // Eliminar espacios al principio y al final

// Verificar si el email ya está registrado
$check_sql = "SELECT * FROM usuarios WHERE usu_email = '$email'";
$check_result = mysqli_query($conn, $check_sql);
$check_google = "SELECT * FROM usuarios WHERE usu_email = '$email' AND usu_pass = ''";
$check_result2 = mysqli_query($conn, $check_google);

if (mysqli_num_rows($check_result2) > 0) {
    echo json_encode(["error" => false]);
}
elseif (mysqli_num_rows($check_result) > 0) {
   echo json_encode(["error" => true, "mensaje" => "El email ya está registrado"]);
} else {
    // Insertar los datos en la base de datos
    $sql = "INSERT INTO usuarios (usu_nombre, usu_email, usu_pass, usu_pregunta, usu_resp) 
            VALUES ('$nombre', '$email', '$pass', '$pregunta', '$respuesta')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["error" => false, "mensaje" => "Usuario insertado correctamente"]);
    } else {
        echo json_encode(["error" => true, "mensaje" => "Error al insertar usuario"]);
    }
}

mysqli_close($conn);
?>
