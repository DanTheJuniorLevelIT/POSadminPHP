<?php
include 'connect.php';

// Monthly Sales Report
$query = "SELECT 
            DATE_FORMAT(t.Date, '%M %Y') AS MonthYear, 
            SUM(td.Price * td.Quantity) AS MonthlySales,
            COUNT(DISTINCT t.transactionID) AS MonthlyTransactionCount
          FROM transaction t
          JOIN transactiondetails td ON t.transactionID = td.transactionID
          GROUP BY MonthYear
          ORDER BY t.Date DESC";

$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_object()) {
    array_push($data,$row);
}

echo json_encode($data);
?>
