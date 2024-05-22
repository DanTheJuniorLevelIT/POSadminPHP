<?php
    include 'connect.php'; 

    $barcode = $_POST['bid'];

    // Query to delete the product
    $query = "DELETE FROM product WHERE Barcode = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $barcode); // Assuming Barcode is an integer

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Product deleted successfully']);
    } else {
        echo json_encode(['error' => 'Error deleting product: ' . $conn->error]);
    }
?>