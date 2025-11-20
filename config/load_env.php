<?php
// Lightweight .env loader. Reads key=value pairs, ignores comments and blank lines.
// Loads into getenv()/$_ENV only if not already set to avoid overriding server vars.

function load_env_file($path) {
  if (!file_exists($path) || !is_readable($path)) {
    return false;
  }
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
      continue;
    }
    list($name, $value) = array_map('trim', explode('=', $line, 2));
    // Strip optional surrounding quotes
    if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
      $value = substr($value, 1, -1);
    }
    if (getenv($name) === false) {
      putenv("{$name}={$value}");
      $_ENV[$name] = $value;
    }
  }
  return true;
}
