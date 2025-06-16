<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

include 'conexion.php';

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

function generarContrasena() {
    $mayusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $minusculas = 'abcdefghijklmnopqrstuvwxyz';
    $numeros = '0123456789';
    
    // Asegurarse de que haya al menos una mayúscula, una minúscula y un número
    $contrasena = '';
    $contrasena .= $mayusculas[rand(0, strlen($mayusculas) - 1)]; // Una mayúscula
    $contrasena .= $minusculas[rand(0, strlen($minusculas) - 1)]; // Una minúscula
    $contrasena .= $numeros[rand(0, strlen($numeros) - 1)]; // Un número
    
    // Rellenar el resto de la contraseña con caracteres aleatorios
    $todosCaracteres = $mayusculas . $minusculas . $numeros;
    for ($i = 3; $i < 8; $i++) { // Ya tenemos 3 caracteres, completar hasta 8
        $contrasena .= $todosCaracteres[rand(0, strlen($todosCaracteres) - 1)];
    }
    
    // Mezclar los caracteres para que no sigan un patrón predecible
    $contrasena = str_shuffle($contrasena);
    
    return $contrasena;
}

if (isset($_POST['id_usu']) && isset($_POST['respuesta'])) {
    $id_usu = $_POST['id_usu'];
    $respuesta = $_POST['respuesta'];

    $stmt = $conn->prepare("SELECT usu_resp, usu_email FROM usuarios WHERE id_usu = ?");
    $stmt->bind_param("i", $id_usu);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($usu_resp, $usu_email);
        $stmt->fetch();

        if (strtolower($usu_resp) == strtolower($respuesta)) {
            // Generar nueva contraseña
            $nueva_pass = generarContrasena();
            
            // Actualizar contraseña
            $stmt_update = $conn->prepare("UPDATE usuarios SET usu_pass = ? WHERE id_usu = ?");
            $stmt_update->bind_param("si", $nueva_pass, $id_usu);
            $stmt_update->execute();
            $stmt_update->close();

            // Configurar PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'soporte.mandangon@gmail.com'; // Tu correo Gmail
                $mail->Password = 'wkrj gwmn tvkw erps'; // Contraseña de aplicación generada
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Remitente y destinatario
                $mail->setFrom('soporte.mandangon@gmail.com', 'Mandangon');
                $mail->addAddress($usu_email);

                // Adjuntar imagen como embebida
                $mail->addEmbeddedImage('img/logo.png', 'logoimg'); // Ruta relativa y nombre CID

                // Contenido del correo
                $mail->isHTML(true);
                $mail->Subject = "Acceso Seguro a tu Cuenta - Mandangon";

                // Cuerpo HTML
                $mail->Body  = "<html>";
                $mail->Body .= "<head>";
                $mail->Body .= "<style>";
                $mail->Body .= "  .email-body { font-family: Arial, sans-serif; line-height: 1.6; }";
                $mail->Body .= "  .logo { text-align: center; margin-bottom: 20px; }";
                $mail->Body .= "  .message { margin: 20px; }";
                $mail->Body .= "</style>";
                $mail->Body .= "</head>";
                $mail->Body .= "<body class='email-body'>";
                $mail->Body .= "<div class='logo'>";
                $mail->Body .= "<img src='cid:logoimg' alt='Mandangon Logo' style='width: 150px;'>";
                $mail->Body .= "</div>";
                $mail->Body .= "<div class='message'>";
                $mail->Body .= "<p>Hola,</p>";
                $mail->Body .= "<p>Tu nueva contraseña es: <b>" . $nueva_pass . "</b></p>";
                $mail->Body .= "<p>Se recomienda que cambies tu contraseña dentro de la sección de ajustes de la aplicación para mayor seguridad.</p>";
                $mail->Body .= "<br>";
                $mail->Body .= "<p>Si tienes alguna consulta o necesitas ayuda, no dudes en contactarnos:</p>";
                $mail->Body .= "<p>Email: soporte.mandangon@gmail.com</p>";
                $mail->Body .= "</div>";
                $mail->Body .= "</body>";
                $mail->Body .= "</html>";

                // Enviar correo
                $mail->send();

                $response = array('status' => 'ok', 'message' => 'Correo enviado');
            } catch (Exception $e) {
                $response = array('status' => 'error', 'message' => "Error al enviar correo: {$mail->ErrorInfo}");
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Respuesta incorrecta');
        }
    } else {
        $response = array('status' => 'error', 'message' => 'Usuario no encontrado');
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Faltan parámetros'));
}
?>
