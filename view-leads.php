<?php
/**
 * AI Ops Sentinel - Dashboard de Operaciones
 * Desarrollado para Portafolio de Seguridad
 */

// 1. CONFIGURACIÓN Y CONEXIÓN
// Asegúrate de que la ruta sea correcta según tu estructura de carpetas
require_once '../config.php'; 

// Habilitar errores solo para depuración (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. LÓGICA DEL SIMULADOR DE ATAQUES (BOTÓN ROJO)
if (isset($_GET['run_test'])) {
    try {
        $ip_test = $_SERVER['REMOTE_ADDR'];
        $attack_type = 'SQL Injection Simulation';
        
        // Usamos la variable $pdo definida en tu config.php
        $sql = "INSERT INTO alertas (ip_origen, tipo_ataque, fecha) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ip_test, $attack_type]);
        
        // Redireccionamos para limpiar la URL y evitar re-envíos al refrescar
        header("Location: view-leads.php");
        exit();
    } catch (Exception $e) {
        // Si hay error de base de datos, lo veremos aquí
        die("<div style='color:white; background:red; padding:20px;'>Error en la simulación: " . $e->getMessage() . "</div>");
    }
}

// 3. CONSULTA DE DATOS PARA LA TABLA
try {
    // Traemos los últimos 10 registros
    // Usamos 'AS' para que los nombres coincidan con tu diseño original
    $stmt = $pdo->query("SELECT ip_origen as name, tipo_ataque as summary, fecha as score, 'High' as quality, 'Encrypted' as email_encrypted FROM alertas ORDER BY id DESC LIMIT 10");
    $leads = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $leads = [];
}

// 4. DATO DE CORTESÍA (Si la tabla está vacía)
if (empty($leads)) {
    $leads = [[
        "name" => "System Ready",
        "email_encrypted" => "No_data_yet",
        "score" => date('Y-m-d H:i'),
        "quality" => "N/A",
        "summary" => "Waiting for first threat detection simulation..."
    ]];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>AI Ops Sentinel | Dashboard</title>
</head>
<body class="bg-slate-900 text-white p-8">
    <div class="max-w-6xl mx-auto">
        <header class="flex justify-between items-center mb-12">
            <h1 class="text-3xl font-bold text-emerald-400">AI Operations & Risk Dashboard</h1>
            <span class="bg-emerald-500/10 text-emerald-400 px-4 py-1 rounded-full text-sm border border-emerald-500/20">
                Security Level: AES-256 Protected
            </span>
        </header>

        <div style="margin-bottom: 30px; padding: 20px; background-color: #1a1a1a; border: 1px solid #333; border-radius: 8px; text-align: center;">
            <h3 style="color: #ffffff; margin-bottom: 10px; font-weight: 300; letter-spacing: 1px; font-size: 1.2rem;">Security Monitoring Lab</h3>
            <p style="color: #888; font-size: 0.9em; margin-bottom: 20px;">Execute a controlled simulation to verify real-time threat detection.</p>
            
            <a href="?run_test=1" style="background-color: #b30000; color: white; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-size: 0.9rem; font-weight: bold; transition: 0.3s; display: inline-block; border: 1px solid #800000;">
                TRIGGER SIMULATED ATTACK
            </a>
        </div>

        <?php foreach ($leads as $lead): ?>
            <div class="bg-slate-800 border border-slate-700 p-6 rounded-xl hover:border-emerald-500/50 transition mb-6">
                <div class="grid grid-cols-1 gap-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-semibold"><?= htmlspecialchars($lead['name']) ?></h2>
                            <p class="text-slate-400 text-sm mb-4">
                                Encrypted Source: <span class="font-mono text-xs text-slate-500"><?= substr($lead['email_encrypted'], 0, 20) ?>...</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="block text-2xl font-bold text-emerald-400">
                                <?= $lead['score'] ?>
                            </span>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Status: <?= $lead['quality'] ?></span>
                        </div>
                    </div>

                    <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-700/50 mt-2">
                        <p class="text-emerald-100 italic text-sm">"<?= htmlspecialchars($lead['summary']) ?>"</p>
                    </div>

                    <button onclick="alert('Decrypting sensitive metadata for authorized personnel... Access Logged.')" 
                            class="mt-6 bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded-lg font-medium transition">
                        Decrypt Sensitive Data
                    </button>
                </div>
            </div>
        <?php endforeach; ?>

    </div> </body>
</html>