<?php
$inputData = json_decode(file_get_contents("php://input"));
$hobbies = $inputData->hobbies;

// Connect to database
$connection = new mysqli("localhost", "root", "", "plmun_system");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch events based on hobbies (this is just an example query)
$query = "SELECT * FROM events WHERE interests LIKE '%$hobbies%'";
$result = $connection->query($query);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode(['events' => $events]);

$connection->close();
?>