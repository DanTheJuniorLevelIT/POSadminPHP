<?php
include 'connect.php';

$query = "
SELECT
    transactions_table.MonthYear,
    transactions_table.TransactionCount,
    COALESCE(returns_table.ReturnCount, 0) AS ReturnCount,
    COALESCE(transactions_table.MonthlySales, 0) AS MonthlySales,
    COALESCE(returns_table.TotalReturnPrice, 0) AS TotalReturnPrice,
    (COALESCE(transactions_table.MonthlySales, 0) - COALESCE(remittance_table.RemittanceAmount, 0)) AS Discrepancy,
    COALESCE(remittance_table.RemittanceAmount, 0) AS RemittanceAmount
FROM
    (
        SELECT
            DATE_FORMAT(t.Date, '%M %Y') AS MonthYear,
            COUNT(DISTINCT t.transactionID) AS TransactionCount,
            SUM(td.Price * td.quantity) AS MonthlySales
        FROM transaction t
        JOIN transactiondetails td ON t.transactionID = td.transactionID
        GROUP BY MonthYear
    ) AS transactions_table
LEFT JOIN
    (
        SELECT
            DATE_FORMAT(r.return_date, '%M %Y') AS MonthYear,
            COUNT(*) AS ReturnCount,
            SUM(p.Price) AS TotalReturnPrice
        FROM returns r
        LEFT JOIN product p ON r.returnprod = p.Barcode
        GROUP BY MonthYear
    ) AS returns_table ON transactions_table.MonthYear = returns_table.MonthYear
LEFT JOIN
    (
        SELECT
            DATE_FORMAT(Date, '%M %Y') AS MonthYear,
            SUM(Amount) AS RemittanceAmount
        FROM remittance
        GROUP BY MonthYear
    ) AS remittance_table ON transactions_table.MonthYear = remittance_table.MonthYear
ORDER BY transactions_table.MonthYear DESC;
";

$result = $conn->query($query);
$data = array();

while ($row = $result->fetch_object()) {
    // Handle cases where there are no returns in a month
    if ($row->ReturnCount === null) {
        $row->ReturnCount = 0; 
    }

    array_push($data, $row);
}

echo json_encode($data);
?>
