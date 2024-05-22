<?php
include 'connect.php';

$day = $_GET['dt'];

// $query = "SELECT c.Lastname, td.Price, td.quantity, p.Prod_name, t.Date FROM transaction t JOIN customer c ON t.CustID = c.CustID JOIN transactiondetails td ON t.transactionID = td.transactionID JOIN product p ON td.Barcode = p.Barcode WHERE t.transactionID = $day";

$query = "SELECT t.transactionID, td.Barcode, p.Prod_name, td.Price, td.quantity, c.Lastname, c.Firstname, DATE_FORMAT(t.Date, '%Y-%m-%d') AS Day FROM transaction t JOIN transactiondetails td ON t.transactionID = td.transactionID JOIN product p ON td.Barcode = p.Barcode JOIN customer c ON t.CustID = c.CustID WHERE DATE_FORMAT(t.Date, '%M %d, %Y') = '$day' ORDER BY t.Date desc";

$result = $conn->query($query);

$data = array();

// if ($row = $result->fetch_object()) {
//     // $response = $result->fetch_object();
//     array_push($data,$row);
// } else {
//     $data = "Error";
// }

while($row = $result->fetch_object()){
    array_push($data,$row);
}

echo json_encode($data);
?>