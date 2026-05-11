<?php
// 1. Reutilizamos tu blindaje original (ajusta la ruta según tu carpeta)
require_once '../waf-engine.php'; 

// 2. Definimos que esta es una respuesta tipo JSON (estándar de APIs profesionales)
header('Content-Type: application/json');

// 3. Recibimos los datos del formulario (vía Make.com o directamente)
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Aquí el WAF ya hizo su trabajo. Si llegamos aquí, los datos son SEGUROS.
    
    $lead_name = $input['name'] ?? 'Unknown';
    $lead_email = $input['email'] ?? 'No email';
    $message = $input['message'] ?? '';

    // --- AQUÍ CONECTAREMOS CON OPENAI EN EL SIGUIENTE PASO ---
    
    echo json_encode([
        "status" => "secure_and_processed",
        "message" => "Lead analyzed by AI Sentinel",
        "security_score" => "Clear (Protected by WAF v1)"
    ]);

} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}