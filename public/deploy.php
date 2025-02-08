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
file_put_contents('../debug.txt', print_r($debug_info, true) . "\n---\n", FILE_APPEND);

// Sekret do weryfikacji żądań
$secret = "680d160ce88e53c3ff9747b8878cfccf5b89d6cf6401683d8b06fe0789f37590";

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

// Katalog z repozytorium
$repo_path = dirname(__DIR__);

// Lista plików, których nie chcemy aktualizować
$protected_files = [
    '.env',
    'includes/config.php',
    '.htaccess',
    'counter.txt',
    'deploy.php',
    'debug.txt'
];

// Zapisz logi
$log_file = '../deploy_log.txt';
function write_log($message) {
    global $log_file;
    $date = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$date] $message\n", FILE_APPEND);
}

try {
    // Zrób backup chronionych plików
    foreach ($protected_files as $file) {
        if (file_exists($file)) {
            copy($file, $file . '.backup');
            write_log("Utworzono backup pliku: $file");
        }
    }

    // Pobierz zmiany z GitHuba
    $output = [];
    exec('cd ' . escapeshellarg($repo_path) . ' && git fetch origin && git reset --hard origin/master 2>&1', $output);
    write_log('Git output: ' . implode("\n", $output));
    
    // Przywróć chronione pliki
    foreach ($protected_files as $file) {
        if (file_exists($file . '.backup')) {
            rename($file . '.backup', $file);
            write_log("Przywrócono plik: $file");
        }
    }
    
    // Wyczyść cache
    if (function_exists('opcache_reset')) {
        opcache_reset();
        write_log('Wyczyszczono cache OPcache');
    }
    
    write_log('Deployment zakończony sukcesem!');
    echo 'Deployment zakończony sukcesem!';
    
} catch (Exception $e) {
    write_log('Błąd: ' . $e->getMessage());
    
    // Przywróć backup w przypadku błędu
    foreach ($protected_files as $file) {
        if (file_exists($file . '.backup')) {
            rename($file . '.backup', $file);
            write_log("Przywrócono backup pliku: $file");
        }
    }
    
    http_response_code(500);
    echo 'Wystąpił błąd podczas deploymentu.';
}
