<?php
include 'connect.php'; // Assuming this file contains your database connection

// Check if the 'bid' parameter is set in the URL
if(isset($_GET['bid'])) {
    $barcode = $_GET['bid'];

    // Prepare the SQL query with the barcode condition
    $query = "SELECT Barcode, Prod_name, CatID, Brand, Size, Price, Description FROM product  WHERE Barcode = '$barcode'";

    // Execute the query
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the row as an associative array
        $row = $result->fetch_assoc();

        // Return the row data as JSON
        echo json_encode($row);
    } else {
        // Handle the case when no matching row is found
        echo json_encode(['error' => 'No product found for the provided barcode']);
    }
} else {
    // Handle the case when the 'bid' parameter is missing
    echo json_encode(['error' => 'Barcode parameter is missing']);
}
?>
