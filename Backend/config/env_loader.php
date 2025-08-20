<?php
// env_loader.php
$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    return;
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    $line = trim($line);

    // Ignorar comentarios
    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }

    // Solo procesar líneas con '='
    if (strpos($line, '=') === false) {
        continue;
    }

    list($name, $value) = explode('=', $line, 2);

    $name = trim($name);
    $value = trim($value, " \t\n\r\0\x0B\"'"); // limpia espacios y comillas

    // Solo usar putenv para que getenv() funcione
    if (getenv($name) === false) {
        putenv("$name=$value");
    }
} 