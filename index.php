<?php

// Conexión a la base de datos SQLite
try {
    $db = new PDO('sqlite:levelup_repairs.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear la tabla si no existe
    $db->exec("
        CREATE TABLE IF NOT EXISTS consultas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            email TEXT NOT NULL,
            telefono TEXT NOT NULL,
            consulta TEXT NOT NULL,
            fecha DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (Exception $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Procesar el formulario si se envía
$mensaje = '';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $consulta = $_POST['consulta'] ?? '';

    // Limpia el número de teléfono: elimina todo excepto dígitos
    $telefono = preg_replace('/[^0-9]/', '', $telefono);

    // Validar el número de teléfono (debe tener 9 dígitos)
    if (!preg_match('/^\d{9}$/', $telefono)) {
        $mensaje = "El número de teléfono debe tener 9 dígitos.";
    } elseif (!empty($nombre) && !empty($email) && !empty($consulta)) {
        try {
            // Inserta la consulta en la base de datos
            $stmt = $db->prepare("
                INSERT INTO consultas (nombre, email, telefono, consulta) 
                VALUES (:nombre, :email, :telefono, :consulta)
            ");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':consulta', $consulta);
            $stmt->execute();

            // Configura PHPMailer
            $mail = new PHPMailer(true);

            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'leveluprepairsesp@gmail.com'; // Cambia esto por tu correo
            $mail->Password = 'cqbx cyfy gjis ymoa'; // Cambia esto por tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // O PHPMailer::ENCRYPTION_SMTPS
            $mail->Port = 587; // Puerto SMTP (587 para TLS, 465 para SSL)

            // Remitente y destinatario
            $mail->setFrom('no-reply@levelUp.com', 'Level UP Repairs');
            $mail->addAddress('leveluprepairsesp@gmail.com'); // Cambia esto por tu correo

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "Nueva consulta de $nombre";
            $mail->Body = "
                <h1>Nueva consulta/reparacion recibida</h1>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Consulta:</strong> $consulta</p>
            ";

            // Envía el correo
            $mail->send();
            $mensaje = "Consulta enviada correctamente. Nos pondremos en contacto contigo pronto.";
        } catch (Exception $e) {
            $mensaje = "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Level UP Repairs - Reparaciones electrónicas de confianza">
    <meta name="author" content="Level UP Repairs">
    <title>Level UP Repairs</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <script src="Script/Script.js"></script>
    <header>
        <img src="LevelUp_Repairs.jpg" alt="Level UP Repairs Logo">
    </header>

    <nav>
        <a href="#home">Inicio</a>
        <a href="#services">Servicios</a>
        <a href="#about">Nosotros</a>
        <a href="#contact">Contacto</a>
    </nav>

    <section id="home">
        <h2>Bienvenidos a Level UP Repairs</h2> 
        <p>En Level UP Repairs nos especializamos en ofrecer soluciones de reparación electrónica de alta calidad. Ya sea que necesites reparar tu teléfono, computadora o cualquier otro dispositivo electrónico, ¡nosotros podemos ayudarte!</p>
    </section>

    <section id="services">
        <h2>Servicios que ofrecemos</h2>
        <div class="services">
            <div class="service-item">
                <h3>Reparación de teléfonos</h3>
                <p>Reparamos pantallas rotas, problemas de batería, y más.</p>
            </div>
            <div class="service-item">
                <h3>Reparación de computadoras</h3>
                <p>Diagnóstico y reparación de laptops y PCs, tanto de hardware como software.</p>
            </div>
            <div class="service-item">
                <h3>Reparación de electrodomésticos</h3>
                <p>Reparaciones rápidas y efectivas de electrodomésticos electrónicos.</p>
            </div>
        </div>
    </section>

    <section id="about">
        <h2>¿Quiénes somos?</h2>
        <p>Level UP Repairs es un equipo de expertos en reparación electrónica con años de experiencia. Nos apasiona devolverle la funcionalidad a tus dispositivos y asegurarnos de que estés 100% satisfecho con el servicio.</p>
    </section>

    <section id="contact">
        <h2>Contacto</h2>
        <div class="contact">
            <div class="contact-item">
                <h3>Ubicacion</h3>
                <p>Getafe, Las Margaritas</p>
            </div>
            <div class="contact-item">
                <h3>Teléfono</h3>
                <p>+34 622 298 076</p>
            </div>
            <div class="contact-item">
                <h3>Correo electrónico</h3>
                <p>Leveluprepairs@gmail.com</p>
            </div>
            <div class="contact-item">
                <h3>WhatsApp</h3>
                <img src="QR Levelup.png" alt="Código QR de WhatsApp" style="max-width: 50%; border-radius: 0px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);">
            </div>
        </div>

        <h3>Envíanos tu consulta</h3>
        <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?php echo (strpos($mensaje, 'Error') !== false) ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
         <?php endif; ?>
        <form id="consulta-form" method="POST">
            <label for="nombre">Tu nombre:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Escribe tu nombre">
            
            <label for="email">Tu correo electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="Escribe tu correo electrónico">
            
            <label for="telefono">Tu número de teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required placeholder="Escribe tu número de teléfono">
            
            <label for="consulta">Tu consulta:</label>
            <textarea id="consulta" name="consulta" required placeholder="Escribe tu consulta aquí..." rows="5"></textarea>
            
            <button type="submit" id="DataSubmit">Enviar consulta</button>
        </form>
    </section>


    <footer>
        <p>&copy; 2025 Level UP Repairs. Todos los derechos reservados.</p>
    </footer>
</body>
</html>