<?php
include 'connect.php';

// Daily & Monthly Sales
$querySales = "SELECT 
                DATE_FORMAT(t.Date, '%b %d, %Y') AS Day,
                DATE_FORMAT(t.Date, '%b %Y') AS Month,
                SUM(td.Price * td.Quantity) AS DailyTotal,
                SUM(SUM(td.Price * td.Quantity)) OVER (PARTITION BY DATE_FORMAT(t.Date, '%b %Y')) AS MonthlySales,
                COUNT(DISTINCT td.TransacDetID) AS TransactionCount 
                FROM transaction t 
                JOIN transactiondetails td ON t.transactionID = td.transactionID
                GROUP BY Day 
                ORDER BY t.Date DESC";
$resultSales = $conn->query($querySales);
$salesData = [];
while ($row = $resultSales->fetch_object()) {
  $salesData[] = $row;
}

// Return & Replace Counts
$queryReturns = "SELECT 
                  COUNT(*) as totalReturns, 
                  SUM(CASE WHEN replacementprod IS NOT NULL THEN 1 ELSE 0 END) as replacedCount,
                  SUM(CASE WHEN returnprod IS NOT NULL THEN 1 ELSE 0 END) as returnedCount
                FROM returns";
$resultReturns = $conn->query($queryReturns);
$returnReplaceData = $resultReturns->fetch_object();

$response = [
  'salesData' => $salesData,
  'returnReplaceData' => $returnReplaceData
];

echo json_encode($response);
?>