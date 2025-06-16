<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar el archivo XML
$xml = simplexml_load_file('https://www.esmadrid.com/opendata/restaurantes_v1_es.xml');

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mandangon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Función para eliminar "de", "del" o "de la" al principio de la dirección
function limpiarDireccion($direccion) {
    return preg_replace('/^(de |del |de la )/i', '', $direccion);
}

// Función para obtener las subcategorías
function obtenerSubcategorias($service) {
    $subcategorias = [];
    if (isset($service->extradata->categorias->categoria)) {
        foreach ($service->extradata->categorias->categoria as $categoria) {
            if (isset($categoria->subcategorias->subcategoria)) {
                foreach ($categoria->subcategorias->subcategoria as $subcategoria) {
                    $subcategorias[] = (string) $subcategoria->item[1];
                }
            }
        }
    }
    return array_unique($subcategorias);
}

// Función para limpiar y extraer los últimos 9 dígitos del número de teléfono
function limpiarTelefono($telefono) {
    $telefono = preg_replace('/\D/', '', $telefono); // Eliminar caracteres no numéricos
    return substr($telefono, -9); // Obtener los últimos 9 caracteres
}

// Función para convertir HTML a texto plano y eliminar entidades HTML
function convertirHtmlATexto($html) {
    $textoPlano = strip_tags($html); // Eliminar etiquetas HTML
    $textoPlano = html_entity_decode($textoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Decodificar entidades HTML
    $textoPlano = trim($textoPlano); // Eliminar espacios adicionales
    return $textoPlano;
}

// Recorrer cada servicio en el XML y extraer los datos necesarios
foreach ($xml->service as $service) {
    $id_rest = $service['id'];
    $rest_nom = mysqli_real_escape_string($conn, convertirHtmlATexto($service->basicData->name)); // Limpiar rest_nom
    $rest_desc = mysqli_real_escape_string($conn, convertirHtmlATexto($service->basicData->body)); // Limpiar rest_desc
    $rest_ubi = limpiarDireccion(mysqli_real_escape_string($conn, $service->geoData->address));
    $rest_tel = limpiarTelefono($service->basicData->phone); // Limpiar y extraer los últimos 9 caracteres del teléfono
    $subcategorias = obtenerSubcategorias($service); // Obtener las subcategorías
    $rest_tipo_com = implode(',', $subcategorias); // Convertir subcategorías a una cadena separada por comas
    $rest_cali = 0; // Esto no está en el XML de ejemplo, debes definir cómo obtenerlo
    $rest_web = mysqli_real_escape_string($conn, $service->basicData->web);

    // Verificar si ya existe un registro con el mismo id_rest
    $check_query = "SELECT * FROM restaurantes WHERE id_rest = '$id_rest'";
    $check_result = $conn->query($check_query);
    
    if ($check_result->num_rows == 0) {
        // Crear la consulta SQL
        $sql = "INSERT INTO restaurantes (id_rest, rest_nom, rest_desc, rest_ubi, rest_tel, rest_tipo_com, rest_cali, rest_web) VALUES ('$id_rest', '$rest_nom', '$rest_desc','$rest_ubi', '$rest_tel', '$rest_tipo_com', '$rest_cali', '$rest_web')";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            echo "Nuevo registro insertado correctamente<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
        }
    } else {
        echo "El registro con id_rest = $id_rest ya existe<br>";
    }
}

// Cerrar la conexión
$conn->close();
?>
