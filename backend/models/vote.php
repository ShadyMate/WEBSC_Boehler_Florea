<?php
class Vote {
    public $id;
    public $nameVonBenutzer;
    public $comment;

    function __construct($nameVonBenutzer, $comment) {
        $this->nameVonBenutzer = $nameVonBenutzer;
        $this->comment = $comment;
    }
    public function getID() { return $this->id; }
    public function setID($id) { $this->id=$id; }
    public function getNameVonBenutzer() { return $this->nameVonBenutzer; }
    public function setNameVonBenutzer($nameVonBenutzer) { $this->nameVonBenutzer=$nameVonBenutzer; }
    public function getComment() { return $this->comment; }
    public function setComment($comment) { $this->comment=$comment; }
}