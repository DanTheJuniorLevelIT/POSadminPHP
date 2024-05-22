<?php
    include 'connect.php';

    $query = "SELECT 
                r.returnid,
                r.CustID,
                p1.Prod_name AS ReturnProductName,
                p2.Prod_name AS ReplacementProductName,
                Date_FORMAT(r.return_date, '%b %d, %Y') AS retDate
                FROM returns r
                JOIN product p1 ON r.returnprod = p1.Barcode
                JOIN product p2 ON r.replacementprod = p2.Barcode
                ORDER BY return_date desc
            ";

    $result = $conn->query($query);

    $data = array();
	
	while($row = $result->fetch_object()){
		array_push($data,$row);
	}
	
	echo json_encode($data);
?>