<?php
	include 'connect.php';
	$postdata= file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$query = "SELECT UserID, Lastname, Firstname, Role, Contact FROM user WHERE Email = '$request->email' AND Password=sha2('$request->pass',224) AND Role = 'Admin'";
	$result = $conn->query($query);
	
	if($result->num_rows!=0){
		$response = $result->fetch_object();
	}else{
		$response = 0;
	}
	
	echo json_encode($response);
?>