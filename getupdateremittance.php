<?php
    include 'connect.php';

    $remitID = $_GET['rid'];

    $query = "SELECT RemittanceID, OrNumber, Date, Amount FROM remittance WHERE RemittanceID = '$remitID'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) { 
        $Data = $result->fetch_assoc();
    } else {
        $Data = ['error' => 'No customer found.'];
    }

    echo json_encode($Data);
?>