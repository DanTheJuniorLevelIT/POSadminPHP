<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $query = "INSERT INTO product(Barcode, CatID, Prod_name, Brand, Size, Price, Description)VALUES('$request->code', '$request->category', '$request->prodname', '$request->brand', '$request->size', '$request->price', '$request->desc')";

    $result = $conn->query($query);

    if($result){
		$msg="Success";
	}else{
		$msg="Duplicate Entry";
	}
	
	echo json_encode($msg);
?>