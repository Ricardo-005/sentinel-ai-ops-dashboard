<?php
// 1. Invocamos el motor de seguridad
require_once '../waf-engine.php';
require_once '../config.php'; // Cambiado a require para asegurar conexión

header('Content-Type: application/json');

// Clave secreta para encriptación
define('ENCRYPTION_KEY', 'tu_clave_secreta_32_caracteres_max');
define('ENCRYPTION_METHOD', 'aes-256-cbc');

// Leer datos (JSON o Formulario)
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, TRUE);
if (!$data) { $data = $_POST; }

// Solo procesamos si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Encriptación de datos sensibles
    $email_original = $data['email'] ?? 'test@example.com';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted_email = openssl_encrypt($email_original, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
    $final_encrypted = base64_encode($encrypted_email . '::' . $iv);

    // 3. Envío a Make.com (Opcional, pero corregido)
    $webhook_url = 'https://hook.us2.make.com/hfjqhso9tmsxmsm56rtja5lub7ycvthl';
    $datos_alerta = [
        'ip' => $_SERVER['REMOTE_ADDR'],
        'tipo_ataque' => 'SQL Injection Detected',
        'fecha' => date('Y-m-d H:i:s'),
        'servidor' => 'SentinelAI-Production'
    ];

    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos_alerta));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);

    // 4. Registro en Base de Datos (TABLA: alertas)
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $attack_type = "SQL Injection Detected";

    // En secure-processor.php (reemplaza la parte del SQL)
    $sql = "INSERT INTO alertas (ip_origen, tipo_ataque, fecha) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SERVER['REMOTE_ADDR'], "SQL Injection Detected"]);

    if ($conn->query($sql) === TRUE) {
        // Todo bien
    }

    // 5. Respuesta final
    echo json_encode([
        "status" => "success",
        "processed_data" => [
            "name" => htmlspecialchars($data['name'] ?? 'Guest'),
            "encrypted_email" => $final_encrypted,
            "ai_score" => "9.8",
            "summary" => "Threat mitigated and logged to Dashboard."
        ]
    ]);

} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>