<?php
  include 'connect.php';

  $retrieve = file_get_contents("php://input");
  $req = json_decode($retrieve);

  $query = "INSERT INTO remittance(OrNumber, Date, Amount) VALUES ('$req->ornumber', '$req->date', '$req->amount')";

  $result = $conn->query($query);

  if($result){
    $reponse = "Success";
  } else {
    $reponse = "Error";
  }

  echo json_encode($reponse);
?>