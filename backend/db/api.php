<?php
require 'config.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

switch ($method) {
    case 'GET':
        // Code to handle GET requests
        break;
    case 'POST':
        // Code to handle POST requests
        break;
    case 'PUT':
        // Code to handle PUT requests
        break;
    case 'DELETE':
        // Code to handle DELETE requests
        break;
}
?>
