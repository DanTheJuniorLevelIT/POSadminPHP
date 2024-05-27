<?php
include 'connect.php'; 

if(isset($_GET['cid'])) {
  $custID = $_GET['cid'];

  $today = date('Y-m-d'); // Get today's date

  // Calculate cutoff dates for the current month
  $cutoff15th = date('Y-m-15'); // 15th of the current month
  $firstOfMonth = date('Y-m-01'); // 1st of the current month
  $imgurl = "http://localhost/customer/image/";


  // Query for customer details
  $customerQuery = "SELECT c.Lastname, c.Firstname, c.Middlename, CONCAT('$imgurl', Profilepic) AS accImg,
                    DATE_FORMAT(c.Birthdate, '%M %e, %Y') AS bdate, 
                    c.Contact, c.Address, c.Credit, c.Charges, 
                    (c.Credit - c.Charges) as Balance 
                    FROM customer c WHERE c.CustID = '$custID'";
  $customerResult = $conn->query($customerQuery);

  // Query for past transactions
  $transactionsQuery = "SELECT t.Date, p.Prod_name, td.quantity, p.Price 
                        FROM customer c 
                        JOIN transaction t ON c.CustID = t.CustID
                        JOIN transactiondetails td ON t.TransactionID = td.TransactionID
                        JOIN product p ON td.Barcode = p.Barcode
                        WHERE c.CustID = '$custID'
                        ORDER BY t.Date desc";
  $transactionsResult = $conn->query($transactionsQuery);
  
  // $transactionsfilter = "SELECT t.Date, p.Prod_name, td.quantity, p.Price, '15th to 29th' AS Period
  //                         FROM customer c 
  //                         JOIN transaction t ON c.CustID = t.CustID
  //                         JOIN transactiondetails td ON t.TransactionID = td.TransactionID
  //                         JOIN product p ON td.Barcode = p.Barcode
  //                         WHERE c.CustID = '$custID' 
  //                           AND MONTH(t.Date) = MONTH(NOW())  
  //                           AND YEAR(t.Date) = YEAR(NOW())     
  //                           AND DAY(t.Date) >= 15 AND DAY(t.Date) < 30
  //                           OR  DAY(t.Date) <= 15 AND DAY(t.Date) > 30
  //                         ORDER BY t.Date DESC";

  // $transactionsfilter = "SELECT t.Date, p.Prod_name, td.quantity, p.Price, CASE 
  //                            WHEN DAY(t.Date) < 15 AND MONTH(t.Date) = MONTH(NOW()) THEN 'Before 15th'
  //                            WHEN DAY(t.Date) >= 15 AND DAY(t.Date) < 30 AND MONTH(t.Date) = MONTH(NOW()) THEN '15th to 29th'
  //                            WHEN (DAY(t.Date) >= 30 OR DAY(t.Date) < 15) AND MONTH(t.Date) = MONTH(NOW() - INTERVAL 1 MONTH) THEN 'Previous Month (30th-14th)'
  //                        END AS Period
  //                         FROM customer c 
  //                         JOIN transaction t ON c.CustID = t.CustID
  //                         JOIN transactiondetails td ON t.TransactionID = td.TransactionID
  //                         JOIN product p ON td.Barcode = p.Barcode
  //                         WHERE c.CustID = '$custID' 
  //                           AND MONTH(t.Date) = MONTH(NOW())  
  //                           AND YEAR(t.Date) = YEAR(NOW())     
  //                           AND DAY(t.Date) >= 15 AND DAY(t.Date) < 30
  //                           OR  DAY(t.Date) <= 15 AND DAY(t.Date) > 30
  //                         ORDER BY t.Date DESC";
  $transactionsfilter = "SELECT t.Date, p.Prod_name, td.quantity, p.Price,
                          CASE 
                              WHEN DAY(NOW()) < 15 AND DAY(t.Date) < 15 THEN 'Before 15th' 
                              WHEN DAY(NOW()) >= 15 AND DAY(NOW()) < 30 
                                  AND DAY(t.Date) >= 15 AND DAY(t.Date) < 30 THEN '15th to 29th'
                              WHEN (DAY(NOW()) >= 30 OR DAY(NOW()) < 15) 
                                  AND (DAY(t.Date) >= 30 OR DAY(t.Date) < 15) AND MONTH(t.Date) = MONTH(NOW() - INTERVAL 1 MONTH) THEN 'Previous Month (30th-14th)'
                              ELSE NULL 
                          END AS Period
                        FROM customer c 
                        JOIN transaction t ON c.CustID = t.CustID
                        JOIN transactiondetails td ON t.TransactionID = td.TransactionID
                        JOIN product p ON td.Barcode = p.Barcode
                        WHERE c.CustID = '$custID'
                        AND MONTH(t.Date) IN (MONTH(NOW()), MONTH(NOW() - INTERVAL 1 MONTH)) 
                        AND t.Date < NOW()                
                        HAVING Period IS NOT NULL
                        ORDER BY t.Date DESC";
  $transactionsResultFilter = $conn->query($transactionsfilter);

  // Fetch data
  if ($customerResult->num_rows > 0) { 
    $customerData = $customerResult->fetch_assoc();
  } else {
    $customerData = ['error' => 'No customer found.'];
  }

  $transactions = [];
  if ($transactionsResult->num_rows > 0) {
    while ($row = $transactionsResult->fetch_assoc()) {
      $transactions[] = $row;
    }
  }

  $transactionsFilterResult = [];
  // if ($transactionsResultFilter->num_rows > 0) {
  //   while ($row = $transactionsResultFilter->fetch_assoc()) {
  //     $transactionsFilterResult[] = $row;
  //   }
  // }

  $totalBefore15th = 0;
  $total15thTo29th = 0;
  $totalPreviousMonth = 0; 

  if ($transactionsResultFilter->num_rows > 0) {
    while ($row = $transactionsResultFilter->fetch_assoc()) {
      $row['prodtotal'] = $row['Price'] * $row['quantity']; // Add 'prodtotal' to the response

      $transactionsFilterResult[] = $row;

      // Calculate totals for each period
      if ($row['Period'] === 'Before 15th') {
          $totalBefore15th += $row['prodtotal'];
      } elseif ($row['Period'] === '15th to 29th') {
          $total15thTo29th += $row['prodtotal'];
      } else {
          $totalPreviousMonth += $row['prodtotal'];
      }
    }
  }

  // Return data as JSON
  echo json_encode([
      'customer' => $customerData,
      'transactions' => $transactions,
      'transactionsFilters' => $transactionsFilterResult,
      'totalBefore15th' => $totalBefore15th,
      'total15thTo29th' => $total15thTo29th,
      'totalPreviousMonth' => $totalPreviousMonth
  ]);

} else {
  echo json_encode(['error' => 'CustID parameter is missing']);
}
?>
