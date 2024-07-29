<?php

// Configure defaults for the whole application.

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Europe/Berlin');

// Settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';
$settings['template'] = $settings['root'] . '/templates';
$settings['logs'] = $settings['root'] . '/logs';

// Error handler
$settings['error'] = [
    // Should be set to false for the production environment
    'display_error_details' => false,
    // Should be set to false for the test environment
    'log_errors' => true,
    // Display error details in error log
    'log_error_details' => true,
];

// Logger settings
$settings['logger'] = [
    'name' => 'app',
    'path' => $settings['logs'],
    'filename' => 'app.log',
    'level' => Monolog\Logger::INFO,
    'file_permission' => 0775,
];

// Database settings
$settings['db'] = [
    'driver' => Cake\Database\Driver\Mysql::class,
    'host' => 'localhost',
    'encoding' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    // Enable identifier quoting
    'quoteIdentifiers' => true,
    // Set to null to use MySQL servers timezone
    'timezone' => null,
    // Disable meta data cache
    'cacheMetadata' => false,
    // Disable query logging
    'log' => false,
    // PDO options
    'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Convert numeric values to strings when fetching.
        // Since PHP 8.1 integers and floats in result sets will be returned using native PHP types.
        // This option restores the previous behavior.
        PDO::ATTR_STRINGIFY_FETCHES => true,
    ],
];

// auth
$settings['auth'] = [
    'fail_interval_min' => 5,
    'fail_attempt_max' => 5,
    'ses_timeout_min' => 60,
    'earlier_pass_max' => 5,
    'earlier_pass_period_days' => 180,
    'pass_expire_days' => 180,
];

// session
$settings['session'] = [
    'name' => 'webapp',
    'cache_expire' => 0,
];

// Console commands
$settings['commands'] = [
    App\Console\SchemaDumpCommand::class,
];

return $settings;
