<?php

declare(strict_types = 1);
// dirname — Returns a parent directory's path
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR); 
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

/* YOUR CODE (Instructions in README.md) */

require( APP_PATH . 'App.php' );
require( APP_PATH . 'helpers.php' );


$files = getTransactionFiles( FILES_PATH );

$transactions = [];
foreach( $files as $file ) {
    // array_merge — Merge one or more arrays
    $transactions = array_merge( $transactions, getTransactions( $file, 'extractTransaction' ) );
}

$totals = calculateTotals( $transactions );

// echo "<pre>";
//     print_r($totals);
// echo "</pre>";

require ( VIEWS_PATH . 'transactions.php' );