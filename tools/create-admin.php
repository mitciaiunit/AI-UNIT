<?php

declare(strict_types=1);

use App\Repositories\AdminUserRepository;

require dirname(__DIR__) . '/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This tool can only be run from the command line.\n");
    exit(1);
}

$username = trim((string) ($argv[1] ?? ''));
$password = (string) ($argv[2] ?? '');
$displayName = trim((string) ($argv[3] ?? ''));

if ($username === '' || $password === '') {
    fwrite(STDERR, "Usage: php tools/create-admin.php <username> <password> [display-name]\n");
    exit(1);
}

if (strlen($username) > 100) {
    fwrite(STDERR, "Username must be 100 characters or fewer.\n");
    exit(1);
}

if (strlen($password) < 12) {
    fwrite(STDERR, "Password must be at least 12 characters.\n");
    exit(1);
}

$users = new AdminUserRepository();
if ($users->usernameExists($username)) {
    fwrite(STDERR, "That username already exists.\n");
    exit(1);
}

$id = $users->create(
    $username,
    password_hash($password, PASSWORD_DEFAULT),
    $displayName === '' ? null : $displayName
);

fwrite(STDOUT, "Created admin user #{$id}: {$username}\n");
