<?php
    include 'connect.php';

    $custID = $_GET['cid'];

    $query = "SELECT CustID, Lastname, Firstname, Middlename, Birthdate, Contact, Address, Credit FROM customer WHERE CustID = '$custID'";

    $customerResult = $conn->query($query);

    if ($customerResult->num_rows > 0) { 
        $customerData = $customerResult->fetch_assoc();
    } else {
        $customerData = ['error' => 'No customer found.'];
    }

    echo json_encode($customerData);
?>