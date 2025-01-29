<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "inv_mang");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete user with confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "DELETE FROM users WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?message=User+deleted+successfully");


        exit;
    } else {
        echo "Error deleting account: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Account Deletion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent background */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .modal button {
            margin: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .delete-btn {
            background-color: #28a745;
            color: white;
        }

        .delete-btn:hover {
            background-color: #218838;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }

        .trigger-btn {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .trigger-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div>
    
        <button class="trigger-btn" >Delete Account</button>
    </div>

  

   
</body>
</html>
