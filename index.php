<?php require_once 'waf-engine.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Secure Enterprise App</title>
</head>
<body class="bg-slate-50 font-sans">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 border border-slate-100">
            <div class="flex justify-center mb-6">
                <div class="bg-emerald-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04a11.357 11.357 0 00-1.018 7.23c.31 1.637 1.052 3.106 2.067 4.312C5.69 19.333 8.32 21 11.25 21c.21 0 .416-.013.618-.037a11.914 11.914 0 004.811-1.637l.013-.01a11.96 11.96 0 003.113-2.613c1.015-1.206 1.757-2.675 2.067-4.312a11.358 11.358 0 00-1.018-7.23z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-center text-slate-800 mb-2">System Secure</h1>
            <p class="text-slate-500 text-center mb-8">This application is protected by an <b>Autonomous Intelligent WAF</b> monitoring traffic 24/7.</p>
            
            <div class="space-y-3">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                    <span class="font-bold text-slate-700">Protected Endpoint:</span>
                    <code class="block text-emerald-600 mt-1">/index.php</code>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm">
                    <span class="font-bold text-slate-700">Security Layer:</span>
                    <span class="block text-slate-600 mt-1">Heuristic & Anomaly Detection</span>
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-slate-400 italic">
                Try injecting SQL or XSS payloads to test the firewall.
            </p>
        </div>
        <!-- Test Console for Recruiters -->
<div class="mt-10 border-t border-slate-100 pt-6">
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Test the Firewall</p>
    <div class="grid grid-cols-1 gap-2">
        <a href="?attack=1' OR '1'='1" 
           class="text-left px-4 py-2 bg-red-50 text-red-600 rounded-lg text-xs hover:bg-red-100 transition-all font-mono">
           🚀 Simulate SQL Injection
        </a>
        <a href="?attack=<script>alert('XSS')</script>" 
           class="text-left px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs hover:bg-blue-100 transition-all font-mono">
           🚀 Simulate XSS Attack
        </a>
        <a href="dashboard.php" 
           class="text-left px-4 py-2 bg-slate-900 text-white rounded-lg text-xs hover:bg-black transition-all text-center font-bold mt-2">
           📊 View Security Dashboard
        </a>
    </div>
</div>
    </div>
</body>
</html>