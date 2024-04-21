<?php
include("models/appointment.php");
include("models/date.php");
include("db.php");

class DataHandler
{
    // Datenbankverbindung
    private $db;

    function __construct()//db.php object
    {
        // Neue Datenbankverbindung
        $this->db = new db();
    }

    // Methode zum Abfragen aller Termine
    public function queryAppointments()
    {
        // Leeres Array für die Termine
        $array = array();

        // Abfrage der Termine von der Datenbank
        $ergebnis = $this->db->queryAppointments();

        // Durchlaufen der Ergebnisse und Erstellen von Terminobjekten
        while ($row = mysqli_fetch_object($ergebnis)) {
            $appointment = new Appointment($row->titel, $row->ort, $row->durationDate, $row->durationTime, $row->info);
            $appointment->setID($row->id);

            // Hinzufügen des Termins zum Array
            array_push($array, $appointment);
        }

        // Rückgabe des Arrays mit den Terminen
        return $array;
    }

    // Methode zum Abfragen von Daten nach Termin
    public function queryDatesByAppointment($idAppointment)
    {
        // Leeres Array für die Daten
        $array = array();

        // Abfrage der Daten von der Datenbank
        $ergebnis = $this->db->queryDatesByAppointment($idAppointment);

        // Durchlaufen der Ergebnisse und Erstellen von Datum- und Zeitobjekten
        while ($row = mysqli_fetch_object($ergebnis)) {
            $dateTime = new DateAndTime($row->date, $row->time);

            // Hinzufügen des Datums und der Zeit zum Array
            array_push($array, $dateTime);
        }

        // Rückgabe des Arrays mit den Daten und Zeiten
        return $array;
    }

    // Methode zum Posten eines Termins
    public function queryPostAppointment($param)
    {
        // Aufteilen des Parameters in einzelne Teile
        $pieces=explode(",",$param);

        // Erstellen eines neuen Datums und einer neuen Zeit
        $now = new DateTime();
        $now->add(new DateInterval("PT{$pieces[2]}H"));
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        // Erstellen eines neuen Terminobjekts
        $appointment=new Appointment($pieces[0],$pieces[1],$date,$time,$pieces[3]);

        // Posten des Termins in der Datenbank
        return $this->db->queryPostAppointment($appointment);
    }

    // Methode zum Posten eines Arrays von Datum und Zeit
    public function queryPostDateTimeArray($param)//$param[0] ist idAppointment, $param[i*2+1] ist date und $param[(i+1)*2] ist time
    {
        // Leeres Array für Datum und Zeit
        $array = array();

        // Ersetzen von "T" durch "," im Parameter
        $param=str_replace("T",",",$param);

        // Aufteilen des Parameters in einzelne Teile
        $pieces=explode(",",$param);

        // Durchlaufen der Teile und Erstellen von Datum- und Zeitobjekten
        for($i=0;$i<5;$i++)
        {
            $dateTime=new DateAndTime($pieces[$i*2+1],$pieces[($i+1)*2]);

            // Hinzufügen des Datums und der Zeit zum Array
            array_push($array, $dateTime);
        }

        // Posten des Arrays von Datum und Zeit in der Datenbank
        if($this->db->queryPostDateTimeArray($pieces[0],$array))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    // Methode zum Buchen eines Termins
    public function queryBookAppointment($param)
    {
        // Aufteilen des Parameters in einzelne Teile
        $pieces=explode(",",$param);

        // Buchen des Termins in der Datenbank
        $ergebnis = $this->db->queryBookAppointment($pieces[0],$pieces[1],$pieces[2]);

        // Abrufen des gebuchten Termins
        $row = mysqli_fetch_object($ergebnis);

        // Rückgabe des gebuchten Termins
        return $row;
    }

    // Methode zum Löschen eines Termins
    public function queryDeleteAppointment($param)
    {
        // Löschen des Termins in der Datenbank
        return $this->db->queryDeleteAppointment($param);
    }

    // Methode zum Abfragen von Abstimmungen nach Termin
    public function queryVotingsByAppointment($param)
    {
        // Leeres Array für die Abstimmungen
        $array = array();

        // Abfrage der Abstimmungen von der Datenbank
        $ergebnis=$this->db->queryVotingsByAppointment($param);

        // Durchlaufen der Ergebnisse und Erstellen von Abstimmungs- und Datum- und Zeitobjekten
        while ($row = mysqli_fetch_object($ergebnis)) {
            $vote = new Vote($row->name, $row->comment);
            $dateTime=new DateAndTime($row->date,$row->time);

            // Hinzufügen der Abstimmung und des Datums und der Zeit zum Array
            array_push($array, $vote);
            array_push($array, $dateTime);
        }

        // Rückgabe des Arrays mit den Abstimmungen und den Daten und Zeiten
        return $array;
    }

    // Methode zum Abrufen der Datenbankverbindung
    function getDB() { return $this->db; }

}