<?php
    include 'connect.php';

    $imgurl = "http://localhost/nlahPOS/img/";

    $query = "SELECT CONCAT('$imgurl', remImg) AS remitIMG, RemittanceID, OrNumber, Date, Amount FROM remittance ORDER BY Date DESC";

    $result = $conn->query($query);

    $data = array();
	
	while($row = $result->fetch_object()){
		array_push($data,$row);
	}
	
	echo json_encode($data);
?>