<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "inv_mang");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Items.Item_id, Items.Item_name, Items.price, Items.quantity, 
        Category.Category_name 
        FROM Items 
        LEFT JOIN Category ON Items.category_id = Category.Category_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Inventory</title>

     <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Table Styling */
        table {
            width: 80%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Table Header Styling */
        th {
            background-color: #007bff;
            color: white;
            text-align: left;
            padding: 12px;
            font-size: 16px;
        }

        /* Table Data Styling */
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            color: #333;
        }

        /* Table Row Hover Effect */
        tr:hover {
            background-color: #f1f3f8;
        }

        /* Remove Border for Last Row */
        tr:last-child td {
            border-bottom: none;
        }
        .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-align: center;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none; /* Remove underline */
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .btn:active {
        background-color: #003f7d;
    }
        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                width: 100%;
            }
            th, td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
    
</head>
<body>

    
<a href="dashboard.php" class="btn">Go to Target Page</a>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['Item_name']; ?></td>
                <td><?php echo $row['Category_name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>