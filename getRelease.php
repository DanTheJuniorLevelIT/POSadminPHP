<?php
    include 'connect.php';

    $imgurl = "http://localhost/nlahPOS/img/";

    $today = date('Y-m-d');

    $query = "SELECT p.Prod_name, r.Quantity, r.Release_Date, DATE_FORMAT(r.Release_Date, '%M %e, %Y') AS reldate, CONCAT('$imgurl', p.imgFile) as img FROM releaseproduct r LEFT JOIN product p ON r.Barcode = p.Barcode WHERE DATE(r.Release_Date) = '$today' ORDER BY r.Release_Date DESC";
    // $query = "SELECT p.Prod_name, r.Quantity, r.Release_Date, DATE_FORMAT(r.Release_Date, '%M %e, %Y') AS reldate, CONCAT('$imgurl', p.imgFile) as img FROM releaseproduct r LEFT JOIN product p ON r.Barcode = p.Barcode ORDER BY r.Release_Date DESC";

    $result = $conn->query($query);

    $data = array();
    // $totalReleasedItems = 0;
    $uniqueProductNames = array();
	
	while($row = $result->fetch_object()){
        // $totalReleasedItems += $row->Quantity;
        $prodname = $row->Prod_name;
        $uniqueProductNames[] = $prodname;
		array_push($data,$row);
	}

    $totalProducts = count($uniqueProductNames);

    $response = array(
        'totalReleasedItems' => $totalProducts,
        'releaseData' => $data
    );
	
	echo json_encode($response);
?>