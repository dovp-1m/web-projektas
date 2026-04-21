<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Database Connection Settings
|--------------------------------------------------------------------------
| Values are read from environment variables first (Docker), then fall
| back to the hard-coded defaults below.
*/
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = [
    'dsn'          => '',
    'hostname'     => getenv('DB_HOST') ?: 'db',
    'username'     => getenv('DB_USER') ?: 'blogcms',
    'password'     => getenv('DB_PASS') ?: 'secret',
    'database'     => getenv('DB_NAME') ?: 'blogcms',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8mb4',
    'dbcollat'     => 'utf8mb4_general_ci',
    'swap_pre'     => '',
    'encrypt'      => FALSE,
    'compress'     => FALSE,
    'stricton'     => FALSE,
    'failover'     => [],
    'save_queries' => TRUE,
];
