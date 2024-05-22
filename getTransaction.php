<?php
    include 'connect.php';

    //Daily Sales Report New
    $query = "SELECT t.transactionID, td.Price, td.quantity, DATE_FORMAT(t.Date, '%M %d, %Y') AS Day, SUM(td.Price * td.Quantity) AS DailyTotal, COUNT(DISTINCT td.TransacDetID) AS TransactionCount FROM transaction t JOIN transactiondetails td ON t.transactionID = td.transactionID GROUP BY Day ORDER BY t.Date DESC";

    $result = $conn->query($query);

    $data = array();
    $TotalSum = 0;
	
	while($row = $result->fetch_object()){
        //Daily Sales Report Old
        // $rowTotal = $row->quantity * $row->Price; // Calculate total for the current row
        $rowTotal = $row->DailyTotal; // Calculate total for the current row
        $TotalSum += $rowTotal; // Accumulate the total sum for all rows

        // Add the row total to the current row data
        $row->RowTotal = $rowTotal;
		array_push($data,$row);
	}

    $response = array(
        'SumPrice' => $TotalSum,
        'salesData' => $data,
    );
	
	echo json_encode($response);
?>