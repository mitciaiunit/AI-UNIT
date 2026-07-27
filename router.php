<?php
// Router script for PHP's built-in server (see README "Quick check without XAMPP").
// Serves real files (assets, etc.) as-is; everything else goes through the
// front controller, same as Apache's public/.htaccess would.
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}
require __DIR__ . '/public/index.php';
