<?php
// Temporary diagnostic file to check why Account menu is not visible
// Access this at: http://localhost/ultimatePOS/public/check-account-menu.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Check if user is authenticated
if (!auth()->check()) {
    echo "<h2>Please log in first</h2>";
    echo "<a href='/ultimatePOS/public/login'>Go to Login</a>";
    exit;
}

echo "<h1>Account Menu Diagnostic</h1>";
echo "<hr>";

// Check 1: User permission
echo "<h2>1. User Permission Check</h2>";
$hasPermission = auth()->user()->can('account.access');
echo "User has 'account.access' permission: ";
echo $hasPermission ? "<strong style='color:green'>✓ YES</strong>" : "<strong style='color:red'>✗ NO</strong>";
echo "<br><br>";

// Check 2: Enabled modules
echo "<h2>2. Enabled Modules Check</h2>";
$enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];
echo "Enabled modules: <pre>" . print_r($enabled_modules, true) . "</pre>";

$accountModuleEnabled = in_array('account', $enabled_modules);
echo "Account module enabled: ";
echo $accountModuleEnabled ? "<strong style='color:green'>✓ YES</strong>" : "<strong style='color:red'>✗ NO</strong>";
echo "<br><br>";

// Final check
echo "<h2>3. Final Result</h2>";
if ($hasPermission && $accountModuleEnabled) {
    echo "<strong style='color:green'>✓ Both conditions are met. The menu SHOULD be visible.</strong><br>";
    echo "If it's still not showing, please clear your browser cache and try again.";
} else {
    echo "<strong style='color:red'>✗ The menu is hidden because:</strong><br><ul>";
    if (!$hasPermission) {
        echo "<li>User does not have 'account.access' permission</li>";
    }
    if (!$accountModuleEnabled) {
        echo "<li>The 'account' module is not enabled in business settings</li>";
    }
    echo "</ul>";

    echo "<h3>Solutions:</h3>";
    if (!$hasPermission) {
        echo "<p><strong>To fix permission:</strong> Go to User Management → Roles → Edit your role → Enable 'account.access' permission under the Accounts section</p>";
    }
    if (!$accountModuleEnabled) {
        echo "<p><strong>To enable the module:</strong> Go to Settings → Business Settings → Enable the 'Account' module</p>";
    }
}

echo "<hr>";
echo "<p><a href='/ultimatePOS/public/home'>Go to Dashboard</a></p>";
