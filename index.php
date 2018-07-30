<?php
/**
 * Index file
 *
 * 
*/

// Error visible
//ini_set( 'display_errors', 1 );

// Composer autoloader
require __DIR__ .  '/vendor/autoload.php';

// Class autoloader
require_once __DIR__ . '/core/Bootstrap.php';

// Load routes
require_once __DIR__ . '/app/routes.php';

// Cron task
require_once __DIR__ . '/app/crons.php';


?>