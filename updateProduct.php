<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    if ($request->code == null) {
      $query = "INSERT INTO product(Barcode, CatID, Prod_name, Brand, Size, Price, Description) VALUES ('$request->code2', '$request->category', '$request->prodname', '$request->brand', '$request->size', '$request->price', '$request->desc')";
    } else {
      $query = "UPDATE product SET CatID='$request->category', Prod_name='$request->prodname', Brand='$request->brand', Size='$request->size', Price='$request->price', Description='$request->desc' WHERE Barcode='$request->code'";
    }

    $result = $conn->query($query);

    $imgurl = "http://localhost/nlahPOS/img/";

    $data = array();
    if ($result) {
      $query = "SELECT p.Barcode, p.CatID, c.Type, p.Prod_name, p.Brand, p.Size, p.Price, p.Description, CONCAT('$imgurl', p.imgFile) as img FROM product p LEFT JOIN category c ON p.CatID = c.CatID";
      
      $result = $conn->query($query); 

      while ($row = $result->fetch_object()) {
        array_push($data, $row);
      }
    } else {
      $data = "Error";
    }

    echo json_encode($data);
?>