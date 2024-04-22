<?php
// Include your db class
//include("db.php");

// Create a new instance of your db class
//$db = new db();

// Test queryAppointments method
// $result = $db->queryAppointments();
// while ($row = mysqli_fetch_object($result)) {
//     echo "Appointment ID: " . $row->id . "\n";
//     echo "Title: " . $row->titel . "\n";
//     echo "Location: " . $row->ort . "\n";
//     echo "Duration Date: " . $row->durationDate . "\n";
//     echo "Duration Time: " . $row->durationTime . "\n";
//     echo "Info: " . $row->info . "\n";
//     echo "------------------------\n";
// }

// // Test queryDatesByAppointment method
// $result = $db->queryDatesByAppointment(1); // replace 1 with a valid appointment ID
// while ($row = mysqli_fetch_object($result)) {
//     echo "Date ID: " . $row->id . "\n";
//     echo "Appointment ID: " . $row->idAppointment . "\n";
//     echo "Date: " . $row->date . "\n";
//     echo "Time: " . $row->time . "\n";
//     echo "------------------------\n";
// }

// // Test queryVotingsByAppointment method
// $result = $db->queryVotingsByAppointment(1); // replace 1 with a valid appointment ID
// while ($row = mysqli_fetch_object($result)) {
//     echo "Date ID: " . $row->id . "\n";
//     echo "Appointment ID: " . $row->idAppointment . "\n";
//     echo "Date: " . $row->date . "\n";
//     echo "Time: " . $row->time . "\n";
//     echo "------------------------\n";
// }

// Test queryDeleteAppointment method
// $result = $db->queryDeleteAppointment(1); // replace 1 with a valid appointment ID
// if ($result) {
//     echo "Appointment deleted successfully.\n";
// } else {
//     echo "Failed to delete appointment.\n";
// }

// Include your db and Appointment class
include("db.php");
include("../models/appointment.php");

// Create a new instance of your db class
$db = new db();

// Create a new Appointment
$appointment = new Appointment("Meeting", "Office", "2024-12-31", "12:00:00", "End of the year meeting");

// Test queryPostAppointment method
$result = $db->queryPostAppointment($appointment);

if ($result instanceof Appointment) {
    echo "Appointment created successfully. ID: " . $result->getID() . "\n";
} else {
    echo "Failed to create appointment.\n";
}