<?php
include("../db/dataHandler.php");
// Für jede Funktion in datahandler gibt es einen switchcase
class SimpleLogic
{
    
    private $dh;

    function __construct()
    {
        $this->dh = new DataHandler();
    }

    function handleRequest($method, $param)
    {
        switch ($method)
        {
            case "queryAppointments":
                $res = $this->dh->queryAppointments();
                break;
            case "queryDatesByAppointment":
                $res = $this->dh->queryDatesByAppointment($param);
                break;
            case "queryPostAppointment":
                echo "test";
                $res = $this->dh->queryPostAppointment($param);
                break;
            case "queryPostDateTimeArray":
                $res = $this->dh->queryPostDateTimeArray($param);
                break;
            case "queryBookAppointment":
                $res = $this->dh->queryBookAppointment($param);
                break;
            case "queryDeleteAppointment":
                $res = $this->dh->queryDeleteAppointment($param);
                break;
            case "queryVotingsByAppointment":
                $res = $this->dh->queryVotingsByAppointment($param);
                break;
            default:
                $res = null;
                break;
        }
        return $res;
    }
    
    function getDH() { return $this->dh; }
}

$simpleLogic = new SimpleLogic();
if (isset($_POST['action']) && isset($_POST['param'])) {
    $method = $_POST['action'];
    $param = $_POST['param'];
} elseif (isset($_GET['action']) && isset($_GET['param'])) {
    $method = $_GET['action'];
    $param = $_GET['param'];
} else {
    echo "no action or param set";
    exit;
}

$result = $simpleLogic->handleRequest($method, $param);
// Hiermit kann man an den Client das ergebnis senden
echo json_encode($result);