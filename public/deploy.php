<?php
// Włącz pełne raportowanie błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sekret do weryfikacji żądań
$secret = "680d160ce88e53c3ff9747b8878cfccf5b89d6cf6401683d8b06fe0789f37590";

// Debug - zapisz informacje o żądaniu
file_put_contents('../debug.txt', date('Y-m-d H:i:s') . "\n" . print_r($_SERVER, true) . "\n---\n", FILE_APPEND);

// Sprawdź czy żądanie pochodzi z GitHuba
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Niedozwolona metoda.');
}

// Pobierz nagłówek z podpisem od GitHuba
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
if (!$signature) {
    http_response_code(401);
    die('Brak podpisu.');
}

// Pobierz zawartość żądania
$payload = file_get_contents('php://input');

// Oblicz hash używając sekretu
list($algo, $hash) = explode('=', $signature, 2);
$calculated_hash = hash_hmac($algo, $payload, $secret);

// Zweryfikuj podpis
if (!hash_equals($hash, $calculated_hash)) {
    http_response_code(401);
    die('Nieprawidłowy podpis.');
}

// Wykonaj git pull
$output = [];
exec('cd ' . escapeshellarg(dirname(__DIR__)) . ' && git pull origin main 2>&1', $output);
file_put_contents('../deploy_log.txt', date('Y-m-d H:i:s') . "\n" . implode("\n", $output) . "\n---\n", FILE_APPEND);

echo "Deployment zakończony!\n";
echo implode("\n", $output);
