<?php
include 'conexion.php';

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id_usu, usu_pregunta FROM usuarios WHERE usu_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usu, $usu_pregunta);
        $stmt->fetch();

        $response = array(
            'status' => 'ok',
            'id_usu' => $id_usu,
            'pregunta' => $usu_pregunta
        );
    } else {
        $response = array('status' => 'error', 'message' => 'Correo no encontrado');
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No email received'));
}
?>
