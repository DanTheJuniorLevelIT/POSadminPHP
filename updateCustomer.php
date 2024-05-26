<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $query = "UPDATE customer SET Lastname='$request->lname', Firstname='$request->fname', Middlename='$request->mname', Birthdate='$request->bdate', Contact='$request->contact', Address='$request->address', Credit= Credit + '$request->credit' WHERE CustID='$request->custid'";

    $customerResult = $conn->query($query);

    if ($customerResult) { 
        $customerData = "Success";
    } else {
        $customerData = "Failed";
    }

    // $credit = $request->credit;
    // if ($credit < 0 || $credit > 1000) {
    //     $customerData = "Error";
    // } else {
    //     $query = "UPDATE customer SET Lastname='$request->lname', Firstname='$request->fname', Middlename='$request->mname', Birthdate='$request->bdate', Contact='$request->contact', Address='$request->address', Credit = Credit + '$request->credit' WHERE CustID='$request->custid'";

    //     $customerResult = $conn->query($query);

    //     if ($customerResult) { 
    //         $customerData = "Success";
    //     } else {
    //         $customerData = "Failed";
    //     }
    // }

    echo json_encode($customerData);
?>