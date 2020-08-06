<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;
use App\Table\PostTable;

Auth::check();

$pdo = Connection::getPDO();
$table = new CategoryTable($pdo);
$table->delete($params['id']);
header('Location: ' . $router->url('admin_categories') . '?delete=1');