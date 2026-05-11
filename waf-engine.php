<?php
// waf-engine.php - Intelligent IDS Engine
require_once 'config.php';

class WAF {
    private $pdo;
    private $threshold = 0.3; // Nivel de sensibilidad

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function analyzeRequest() {
        // Analizamos GET y POST para detectar inyecciones
        $inputs = array_merge($_GET, $_POST);
        
        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                $this->inspect($value);
            }
        }
    }

    private function inspect($payload) {
        $score = 0.0;
        $reason = "Unknown Anomaly";

        // 1. Detección por Firmas (Heurística)
        $sqlPatterns = '/(UNION\s+SELECT|SELECT\s+\*|DROP\s+TABLE|OR\s+1=1|--|#)/i';
        $xssPatterns = '/(<script>|javascript:|onerror=|onload=|<img\s+src=)/i';

        if (preg_match($sqlPatterns, $payload)) {
            $score += 0.8;
            $reason = "SQL Injection Pattern";
        } 
        
        if (preg_match($xssPatterns, $payload)) {
            $score += 0.8;
            $reason = "XSS Script Injection";
        }

        // 2. Detección por Anomalía (IA Básica: Densidad de caracteres)
        // Los hackers usan muchos caracteres especiales en poco espacio
        $length = strlen($payload);
        if ($length > 4) {
            $specialChars = preg_match_all('/[^\w\s]/', $payload);
            $density = $specialChars / $length;
            if ($density > 0.35) {
                $score += 0.4;
                if ($reason == "Unknown Anomaly") $reason = "High Character Density";
            }
        }

        // Acción de Bloqueo y Registro
        if ($score >= $this->threshold) {
            $this->logAndBlock($payload, $reason, $score);
        }
    }

    private function logAndBlock($payload, $type, $score) {
        // Guardamos en la base de datos
        try {
            $stmt = $this->pdo->prepare("INSERT INTO waf_logs (ip_address, attack_type, payload, risk_score) VALUES (?, ?, ?, ?)");
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $stmt->execute([$ip, $type, htmlspecialchars($payload), $score]);
        } catch (Exception $e) {
            // Si falla la DB, seguimos bloqueando por seguridad
        }

        // Mostramos pantalla de bloqueo profesional en Inglés para Upwork
        http_response_code(403);
        die('
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <script src="https://cdn.tailwindcss.com"></script>
            <title>Access Denied | Security Shield</title>
        </head>
        <body class="bg-black text-white flex items-center justify-center h-screen font-mono">
            <div class="border-2 border-red-600 p-10 max-w-lg shadow-[0_0_50px_rgba(220,38,38,0.3)] bg-zinc-900">
                <h1 class="text-5xl font-bold text-red-600 mb-4 tracking-tighter">403 FORBIDDEN</h1>
                <p class="text-xl text-zinc-400 mb-6 uppercase tracking-widest border-b border-zinc-700 pb-4">Security Threat Detected</p>
                <p class="text-sm text-zinc-500 mb-8 leading-relaxed">
                    Your request was flagged by our <b>Autonomous WAF Engine</b>. 
                    Pattern identified: <span class="text-red-400">'.$type.'</span>. 
                    Your IP has been logged for further investigation.
                </p>
                <div class="flex justify-between items-center text-xs text-red-500/50 uppercase">
                    <span>Engine: Intelligent IDS v1.0</span>
                    <span>Status: Logged</span>
                </div>
            </div>
        </body>
        </html>
        ');
    }
}

// Inicialización automática
$waf = new WAF($pdo);
$waf->analyzeRequest();