<?php
class db
{
    private $con;
    // Konstruktor der Klasse
    function __construct() {
        $this->con = mysqli_connect('localhost', 'bif2webscriptinguser', 'bif2021');
        // DB wird selected, wenn sie nicht gefunden wird, wird eine neue erstellt
        $selectAlreadyCreatedDatabase = mysqli_select_db($this->con, "appointmentfinder");
        if (!$selectAlreadyCreatedDatabase)
        {
            $createNewDb = "CREATE DATABASE IF NOT EXISTS `appointmentfinder`";
            if (mysqli_query($this->con, $createNewDb)) {
                echo "Database created successfully.<br>";
            } else {
                echo "Error creating database: " . mysqli_error($this->con) . "<br>";
            }
        }
        // DB wird selected
        $selectCreatedDatabase = mysqli_select_db($this->con, "appointmentfinder");

        if ($selectCreatedDatabase) {
            $sqlCreateTable1 = "
              CREATE TABLE IF NOT EXISTS `appointments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `titel` varchar(100) NOT NULL,
                `ort` varchar(100) NOT NULL,
                `durationDate` date NOT NULL,
                `durationTime` time NOT NULL,
                `info` varchar(100) NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            $sqlCreateTable2 = "
              CREATE TABLE IF NOT EXISTS `dates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `idAppointment` int(11) NOT NULL,
                `date` date NOT NULL,
                `time` time NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (idAppointment) REFERENCES appointments(id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            $sqlCreateTable3 = "
                CREATE TABLE IF NOT EXISTS `userDate` (
                  `idDate` int(11) NOT NULL,
                  `name` varchar(100) NOT NULL,
                  `comment` varchar(100),
                  FOREIGN KEY (idDate) REFERENCES dates(id)
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            // Hier werden die oben deklarierten tables deklariert
            mysqli_query($this->con, $sqlCreateTable1);
            mysqli_query($this->con, $sqlCreateTable2);
            mysqli_query($this->con, $sqlCreateTable3);
        }
    }

    public function queryAppointments()
    {
        // Alle Appointments
        $query = "SELECT * FROM appointments";
        if ($ergebnis = mysqli_query($this->con, $query)) {
            // Wenn die Query erfolgreich ist, Ergebnis
            return $ergebnis;
        } else {
            return 0;
        }
    }
    public function queryDatesByAppointment($idAppointment)
    {
        // Special Characters werden escaped um benützt zu werden
        $idOfAppointment = mysqli_real_escape_string($this->con, $idAppointment);
        // Dates von Appointment geholt
        $query = "SELECT * FROM `dates` WHERE `idAppointment`='" . $idAppointment . "'";

        if ($ergebnis = mysqli_query($this->con, $query)) {
            return $ergebnis;
        } else {
            return 0;
        }
    }

    public function queryPostAppointment(Appointment $appointment)
    {
        // SQL Query zum einfügen
        $query1="INSERT INTO `appointments`(`titel`, `ort`, `durationDate`, `durationTime`, `info`) VALUES ('".$appointment->getTitel()."','".$appointment->getOrt()."','".$appointment->getDurationDate()."','".$appointment->getDurationTime()."','".$appointment->getInfo()."')";
        if(mysqli_query($this->con, $query1))
        {
            // Wenn die Query erfolgreich ist wird das Appointment inserted
            $query2 = "SELECT * FROM `appointments` WHERE `titel`='" . $appointment->getTitel() . "' AND `ort`='".$appointment->getOrt()."' AND `durationDate`='".$appointment->getDurationDate()."' AND `durationTime`='".$appointment->getDurationTime()."' AND `info`='".$appointment->getInfo()."'";
            $ergebnis = mysqli_query($this->con, $query2);
            $row = mysqli_fetch_object($ergebnis);
            // Neues Appointment Objekt
            $appointment = new Appointment($row->titel, $row->ort, $row->durationDate, $row->durationTime, $row->info);
            $appointment->setID($row->id);
            return $appointment;
        }
        else
        {
            return 0;
        }
    }
    public function queryPostDateTimeArray($idAppointment, $dateTimeArray)
    {
        // Durchs Date-Time Array gehen
        for($i=0;$i<5;$i++)
        {
            // SQL Query um date-time einzugeben
            $query="INSERT INTO `dates`(`idAppointment`,`date`, `time`) VALUES ('". $idAppointment ."','". $dateTimeArray[$i]->getDate() ."','". $dateTimeArray[$i]->getTime() ."')";
            if(!mysqli_query($this->con, $query))
            {
                return 0;
            }
        }
        return 1;
    }
    public function queryBookAppointment($id,$name,$comment)
    {
        // Special Characters für die benützung escapen
        $id = mysqli_real_escape_string($this->con, $id);
        $name = mysqli_real_escape_string($this->con, $name);
        $comment = mysqli_real_escape_string($this->con, $comment);
        // SQL Query
        $query="INSERT INTO `userdate`(`idDate`,`name`,`comment`) VALUES ('". $id ."','".$name."','".$comment."')";
        if(mysqli_query($this->con, $query))
        {
            // Wenn die Query erfolgreich ist wird sie eingefügt
            $returnQuery = "SELECT * FROM `dates` WHERE `id`='" . $id."'";
            if($ergebnis = mysqli_query($this->con, $returnQuery));
            return $ergebnis;
        }
        else
        {
            return 0;
        }
    }
    public function queryDeleteAppointment($id)
    {
        // Userdate löschen
        $id = mysqli_real_escape_string($this->con, $id);
        $sql1 = "DELETE ud FROM `userdate` ud INNER JOIN `dates` d ON ud.idDate=d.id WHERE d.idAppointment='" . $id . "'";
        // Datum löschen
        $temp1=mysqli_query($this->con, $sql1);
        $sql2 = "DELETE FROM `dates` WHERE idAppointment=" . $id;
        // Appointment löschen
        $temp2=mysqli_query($this->con, $sql2);
        $sql3 = "DELETE FROM `appointments` WHERE id=" . $id;
        $temp3=mysqli_query($this->con, $sql3);
        if($temp1&&$temp2)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    public function queryVotingsByAppointment($id)
    {
        $id = mysqli_real_escape_string($this->con, $id);
        // SQL Query um Votes vom Appointment zu selecten
        $query = "SELECT * FROM `dates` INNER JOIN `userdate` ON userdate.idDate=dates.id WHERE dates.idAppointment='" . $id . "'";
        if ($ergebnis = mysqli_query($this->con, $query)) {
            // Gibt Ergebnis aus falls erfolgreich
            return $ergebnis;
        } else {
            return 0;
        }
    }

    // Methode um Connection zu bekommen
    function getCon() { return $this->con; }
}