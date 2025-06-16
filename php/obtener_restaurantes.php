<?php
include 'conexion.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Consulta para obtener todos los restaurantes con su puntuación media
$sql = "
    SELECT r.*, 
           IFNULL(AVG(res_cali), 0) AS puntuacion_media 
    FROM restaurantes r
    LEFT JOIN resenias re ON r.id_rest = re.res_rest
    GROUP BY r.id_rest
";

$result = $conn->query($sql);

$restaurantes = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $restaurantes[] = $row;
    }
    // Convertir el resultado a formato JSON y devolverlo
    echo json_encode($restaurantes);
} else {
    echo json_encode(array('mensaje' => 'No se encontraron restaurantes.'));
}

$conn->close();
?>
