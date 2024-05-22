<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $query = "INSERT INTO releaseproduct(Barcode, Quantity, Release_Date)VALUES('$request->code', '$request->quantity', CURDATE())";

    $result = $conn->query($query);
    $data = array();

    if($result){
		  $query = "SELECT p.Prod_name, r.Quantity, r.Release_Date, DATE_FORMAT(r.Release_Date, '%M %e, %Y') AS reldate FROM releaseproduct r LEFT JOIN product p ON r.Barcode = p.Barcode ORDER BY r.Release_Date DESC";
      $result = $conn->query($query);

      while($row = $result->fetch_object()){
        array_push($data,$row);
      }
    }else{
      $msg="Error";
    }
	
	echo json_encode($data);
?>