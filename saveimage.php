<?php
    include 'connect.php';

    $bid = $_POST['bid'];
    $imgurl = "http://localhost/nlahPOS/img/";

    $response = new stdClass();
    if(!empty($_FILES['files'])){
        $path = $_FILES['files']['name'];
        $expname = explode('.',$path);
        $newpath = $expname[0].$bid.".".$expname[1];
        $ext = pathinfo($newpath, PATHINFO_EXTENSION);
            $targetDir = "./img/";
            $targetFilePath = $targetDir . $newpath;

        if(move_uploaded_file($_FILES["files"]["tmp_name"], $targetFilePath)){
            $query = "UPDATE product SET imgFile='$newpath' WHERE Barcode='$bid'";
            if($result = $conn->query($query)){
            //     $query = "SELECT p.Barcode, p.CatID, c.Type, p.Prod_name, p.Brand, p.Size, p.Price, p.Description, CONCAT('$imgurl', p.imgFile) as img FROM product p LEFT JOIN category c ON p.CatID = c.CatID";

            //     $result = $conn->query($query);

            //     $data = array();

            //     while($row = $result->fetch_object()){
            //         array_push($data,$row);
            //     }
            // }else{
            //     $data = "0";
            include 'getproduct.php';
            }
        }
    }

    // echo json_encode($data);
?>