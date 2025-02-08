<?php
// Włącz pełne raportowanie błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug - zapisz podstawowe informacje o żądaniu
$debug_info = [
    'time' => date('Y-m-d H:i:s'),
    'server' => $_SERVER,
    'post_data' => file_get_contents('php://input')
];
file_put_contents('debug.txt', print_r($debug_info, true) . "\n---\n", FILE_APPEND);

// Sekret do weryfikacji żądań
$secret = "680d160ce88e53c3ff9747b8878cfccf5b89d6cf6401683d8b06fe0789f37590"; // Wygenerowany bezpieczny sekret

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

// Zweryfikuj, czy podpis się zgadza
if (!hash_equals($hash, $calculated_hash)) {
    http_response_code(401);
    die('Unauthorized');
}

// Logi deploymentu
$logFile = __DIR__ . '/../storage/logs/deployment.log';

// Funkcja do logowania
function logMessage($message) {
    global $logFile;
    $date = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$date] $message\n", FILE_APPEND);
}

// Wykonaj git pull
$output = [];
$result = 0;

// Przejdź do katalogu głównego projektu
chdir(__DIR__ . '/..');

// Wykonaj komendy
$commands = [
    'git reset --hard HEAD',
    'git pull origin master',
    'composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader',
    'php artisan migrate --force',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
    'chmod -R 775 storage bootstrap/cache'
];

foreach ($commands as $command) {
    exec($command . ' 2>&1', $output, $result);
    logMessage("Executing: $command");
    logMessage("Result: " . ($result === 0 ? 'SUCCESS' : 'FAILED'));
    logMessage("Output: " . implode("\n", $output));
    
    if ($result !== 0) {
        http_response_code(500);
        die("Command failed: $command");
    }
}

echo "Deployment successful!\n";
logMessage("Deployment completed successfully");
