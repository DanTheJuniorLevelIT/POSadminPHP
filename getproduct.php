<?php
    include 'connect.php';

    $imgurl = "http://localhost/nlahPOS/img/";

    $query = "SELECT p.Barcode, p.CatID, c.Type, p.Prod_name, p.Brand, p.Size, p.Price, p.Description, CONCAT('$imgurl', p.imgFile) as img FROM product p LEFT JOIN category c ON p.CatID = c.CatID";

    $result = $conn->query($query);

    $data = array();
	
	while($row = $result->fetch_object()){
		array_push($data,$row);
	}
	
	echo json_encode($data);
?>