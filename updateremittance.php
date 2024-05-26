<?php
    include 'connect.php';
    $retrieve = file_get_contents("php://input");
    $req = json_decode($retrieve);

    $imgurl = "http://localhost/nlahPOS2/img/";

    if ($req->remitID == null) {
        $query = "INSERT INTO remittance(OrNumber, Date, Amount) VALUES ('$req->ornumber', '$req->date', '$req->amount');";
    } else {
        $query = "UPDATE remittance SET OrNumber='$req->ornumber', Date='$req->date', Amount='$req->amount' WHERE RemittanceID='$req->remitID';";
    }

    $result = $conn->query($query);

    $data = array();

    if($result){
        $query = "SELECT CONCAT('$imgurl', remImg) AS remitIMG, RemittanceID, OrNumber, Date, Amount FROM remittance ORDER BY Date DESC";

        $result = $conn->query($query);
        
        while($row = $result->fetch_object()){
            array_push($data,$row);
        }
        // $data = "Success";
    } else {
        $data = "Error";
    }

    echo json_encode($data);
?>