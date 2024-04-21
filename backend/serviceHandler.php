<?php
include("businesslogic/simpleLogic.php");
// serviceHandler.php ist die Funktion, die durch Ajax-Aufrufe aufgerufen wird
// Sie nimmt Parameter und Methode per GET entgegen und ruft dann simpleLogic.php auf, um den entsprechenden Request auszuführen
$param = "";
$method = "";

// Überprüfen, ob die GET-Parameter "method" und "param" gesetzt sind
isset($_GET["method"]) ? $method = $_GET["method"] : false;
isset($_GET["param"]) ? $param = $_GET["param"] : false;

$logic = new SimpleLogic();

// Aufrufen der handleRequest-Methode mit den übergebenen Parametern
$result = $logic->handleRequest($method, $param);

if ($result == null) {
    response("GET", 400, null);
} else {
    response("GET", 200, $result);
}

// HTTP Antwort
function response($method, $httpStatus, $data)
{
    header('Content-Type: application/json');

    // Methodenprüfung
    switch ($method) {
        case "GET":
            http_response_code($httpStatus);
            echo (json_encode($data));
            break;
        default:
            http_response_code(405);
            echo ("Method not supported yet!");
    }
}