<?php
    include 'connect.php';

    $today = date('d'); 
    $currentHour = date('H'); 

    if (($today == '15' || $today == '30') && $currentHour == '01') {
        // Update Charges to 0 (zero) for all customers only at 1 AM
        $updateQuery = "UPDATE customer SET Charges = 0";
        $conn->query($updateQuery);
    }

    $query = "SELECT CustID, Lastname, Firstname, Middlename, DATE_FORMAT(Birthdate, '%M %e, %Y') AS bdate, Contact, Address, Credit, Charges, (Credit - Charges) as Balance FROM customer WHERE CustID <> 1";

    $result = $conn->query($query);

    $data = array();
	
	while($row = $result->fetch_object()){
		array_push($data,$row);
	}
	
	echo json_encode($data);
?>