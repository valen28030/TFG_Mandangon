<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include("conexion.php");

$email = $_GET["email"];
$password = $_GET["pass"];

if ($password == "") {
    $sql = "SELECT * FROM usuarios WHERE usu_email = '$email'";
} else {
    $sql = "SELECT * FROM usuarios WHERE usu_email = '$email' AND usu_pass = '$password'";
}

$result = mysqli_query($conn, $sql) or die(json_encode([
    'error' => true,
    'mensaje' => 'Error en la consulta'
]));

// SOLO HACEMOS FETCH SI HAY RESULTADOS
if (mysqli_num_rows($result) == 0) {
    // Usuario no encontrado
    $response = array(
        'error' => true,
        'mensaje' => 'Usuario no encontrado',
    );
} else {
    $row = mysqli_fetch_array($result); // <<< AQUÍ recién
    $response = array(
        'error' => false,
        'id' => $row['id_usu'],
        'nombre' => $row['usu_nombre']
    );
}

echo json_encode($response);
?>
