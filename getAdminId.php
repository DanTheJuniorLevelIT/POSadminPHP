<?php
    include 'connect.php';

    $ADID = $_GET['aid'];

    $query = "SELECT UserID, Birthdate, Contact, Address FROM user WHERE UserID = '$ADID'";

    $customerResult = $conn->query($query);

    if ($customerResult->num_rows > 0) { 
        $customerData = $customerResult->fetch_assoc();
    } else {
        $customerData = ['error' => 'No customer found.'];
    }

    echo json_encode($customerData);
?>