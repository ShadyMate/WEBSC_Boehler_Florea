<?php
//class for Appointment
//it contains all varaibles as database table
class Appointment {
    public $id;
    public $titel;
    public $ort;
    public $durationDate;
    public $durationTime;
    public $info;

    function __construct($titel, $ort, $durationDate, $durationTime, $info) {
        $this->titel = $titel;
        $this->ort = $ort;
        $this->durationDate = $durationDate;
        $this->durationTime = $durationTime;
        $this->info = $info;
    }
    public function getID() { return $this->id; }
    public function setID($id) { $this->id=$id; }
    public function getTitel() { return $this->titel; }
    public function setTitel($titel) { $this->titel=$titel; }
    public function getOrt() { return $this->ort; }
    public function setOrt($ort) { $this->ort=$ort; }
    public function getDurationDate() { return $this->durationDate; }
    public function setDurationDate($durationDate) { $this->durationDate=$durationDate; }
    public function getDurationTime() { return $this->durationTime; }
    public function setDurationTime($durationTime) { $this->durationTime=$durationTime; }
    public function getInfo() { return $this->info; }
    public function setInfo($info) { $this->info=$info; }
}