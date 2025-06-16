<?php
include 'conexion.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

if (isset($_GET['rest_id'])) {
    $restauranteId = $_GET['rest_id'];

    // Consulta para obtener las reseñas de un restaurante específico
    $sql = "
    SELECT r.res_desc, r.res_cali, r.res_fecha, u.usu_nombre 
    FROM resenias r
    JOIN usuarios u ON r.res_usu = u.id_usu
    WHERE r.res_rest = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $restauranteId);
        $stmt->execute();
        $result = $stmt->get_result();

        $resenias = array();
        $totalPuntos = 0;
        $cantidad = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resenias[] = $row;
                $totalPuntos += $row['res_cali'];
                $cantidad++;
            }

            // Calcular media
            $media = $cantidad > 0 ? round($totalPuntos / $cantidad, 2) : 0;

            // Devolver tanto las reseñas como la media
            echo json_encode(
                array(
                    'resenias' => $resenias,
                    'media' => $media
                ),
                JSON_NUMERIC_CHECK
            );
        } else {
            echo json_encode(array(
                'mensaje' => 'No se encontraron reseñas para este restaurante.',
                'media' => 0,
                'resenias' => []
            ));
        }

        $stmt->close();
    } else {
        echo json_encode(array('mensaje' => 'Error en la consulta.'));
    }
} else {
    echo json_encode(array('mensaje' => 'No se ha enviado un id de restaurante.'));
}

$conn->close();
?>
