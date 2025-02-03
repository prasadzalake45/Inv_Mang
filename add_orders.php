<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $customer_address = $conn->real_escape_string($_POST['customer_address']);

    $sql = "INSERT INTO orders (customer_name, customer_address) VALUES ('$customer_name', '$customer_address')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Order successfully!');
                window.location.href = 'dashboard.php';
              </script>";
        
    } else {
        $message = "Error: " . $conn->error;
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

<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        form {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            margin-bottom: 20px;
            color: #343a40;
            text-align: center;
        }

        div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #495057;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 1rem;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-size: 1rem;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
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












