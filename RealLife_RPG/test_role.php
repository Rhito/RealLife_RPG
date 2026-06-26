<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = \App\Models\Admin::first();
echo "Admin Role type: " . get_class($admin->role) . "\n";
echo "Admin Role value: " . $admin->role->value . "\n";
