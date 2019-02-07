<?php

require_once __DIR__.'/vendor/autoload.php';

use Siler\Functional as λ;
use Siler\Route;

Route\resource('/', 'public');

Route\get('/clients/', 'api/clients/index.php');
Route\get('/clients/search/', 'api/clients/search.php');
Route\get('/clients/update/', 'api/clients/update.php');
Route\get('/clients/delete/', 'api/clients/delete.php');