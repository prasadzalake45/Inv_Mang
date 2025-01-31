<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
include 'db_connection.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    
    $customer_name=$conn->real_esacpe_string($POST['customer_name'])
    $customer_address=$conn->real_escape_string($POST['customer_address'])
    


    $sql="INSERT INTO orders (customer_name,customer_address) VALUES ('$customer_name','$customer_address')";

    if($conn->query($sql)===TRUE){
        $message="Successfully added Orders";
    }
    else{
        $message="Error" "$conn->error";

    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form method="POST">
    <div>
    <label for="customer_name">Customer Name</label>
    <input type="text" name="customer_name" required/>
    </div>

    <div>
    <label for="customer_address">Customer Address</label>
    <input type="text" name="customer_address" required/>
    </div>
    <button type="submit">Submit</button>
    

</form>

    
</body>
</html>












