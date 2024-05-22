<?php
    include 'connect.php';

    $ADID = $_GET['aid'];
    $imgurl = "http://localhost/nlahPOS/img/";

    $query = "SELECT UserID, Lastname, Firstname, Role, DATE_FORMAT(Birthdate, '%M %e, %Y') AS FormattedBirthdate, Contact, Address,  CONCAT('$imgurl', profilepic) as img FROM user WHERE UserID = '$ADID'";

    $customerResult = $conn->query($query);

    if ($customerResult->num_rows > 0) { 
        $customerData = $customerResult->fetch_assoc();
    } else {
        $customerData = ['error' => 'No customer found.'];
    }

    echo json_encode($customerData);
?>