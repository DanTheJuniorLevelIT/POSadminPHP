<?php
    // include 'connect.php';
    // $postdata = file_get_contents("php://input");
    // $request = json_decode($postdata);

    // $query = "UPDATE user SET Birthdate='$request->bdate', Contact='$request->contact', Address='$request->address' WHERE UserID='$request->id'";

    // $customerResult = $conn->query($query);

    // if ($customerResult) { 
    //     $customerData = "Success";
    // } else {
    //     $customerData = "Failed";
    // }

    // echo json_encode($customerData);

    // include 'connect.php';
    // $postdata = file_get_contents("php://input");
    // $request = json_decode($postdata);

    // // Retrieve the hashed password from the database for the user
    // $query = "SELECT Password FROM user WHERE UserID='$request->id'";
    // $result = $conn->query($query);

    // if ($result->num_rows > 0) {
    //     $row = $result->fetch_assoc();
    //     $hashedPassword = $row['Password'];

    //     // Hash the input old password using SHA-2
    //     $inputOldPassword = hash('sha224', $request->oldpassword);

    //     // Compare the hashed input old password with the hashed password from the database
    //     if ($hashedPassword == $inputOldPassword) {
    //         // If old password matches, update the user's details
    //         $updateQuery = "UPDATE user SET Birthdate='$request->bdate', Contact='$request->contact', Address='$request->address', Password=sha2('$request->newpassword',224) WHERE UserID='$request->id'";
    //         $updateResult = $conn->query($updateQuery);

    //         if ($updateResult) {
    //             $response = "Success";
    //         } else {
    //             $response = "Failed to update user details";
    //         }
    //     } else {
    //         $response = "Incorrect old password";
    //     }
    // } else {
    //     $response = "User not found";
    // }

    // echo json_encode($response);

    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    // Prepare the initial part of the SQL query
    $query = "UPDATE user SET Birthdate='$request->bdate', Contact='$request->contact', Address='$request->address'";

    // Check if the old password is provided
    if (!empty($request->oldpassword)) {
        // Retrieve the hashed password from the database
        $selectQuery = "SELECT Password FROM user WHERE UserID='$request->id'";
        $result = $conn->query($selectQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['Password'];

            // Hash the input old password
            $inputOldPassword = hash('sha224', $request->oldpassword);

            // Compare hashed passwords
            if ($hashedPassword === $inputOldPassword) {
                // Append password update to the query
                $query .= ", Password=sha2('$request->newpassword',224)";
            } else {
                $response = "Incorrect old password";
                echo json_encode($response);
                exit; // Stop further processing
            }
        } else {
            $response = "User not found";
            echo json_encode($response);
            exit; // Stop further processing
        }
    }

    // Complete the query with the WHERE clause
    $query .= " WHERE UserID='$request->id'";

    // Execute the final query
    $updateResult = $conn->query($query);

    if ($updateResult) {
        $response = "Success";
    } else {
        $response = "Failed to update user details";
    }

    echo json_encode($response);
?>