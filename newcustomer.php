<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $query = "INSERT INTO customer(Lastname, Firstname, Middlename, Birthdate, Contact, Address, Credit, Email, Password)VALUES('$request->lname', '$request->fname', '$request->mname', '$request->bdate', '$request->contact', '$request->address', '$request->credit', '$request->email', sha2('$request->pass', 224))";

    $result = $conn->query($query);

    if($result){
		$msg="Success";
    }else{
      $msg="Duplicate Entry";
    }
	
	echo json_encode($msg);
?>