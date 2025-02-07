<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

$user_id = $_SESSION['id'];  // Get logged-in user's ID

// Fetch the user's cart items
$sql = "SELECT o.item_id, i.Item_name, o.quantity, o.price, o.quantity * o.price as total_price
        FROM order_details o
        JOIN Items i ON o.item_id = i.Item_id
        WHERE o.id = '$user_id'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
    /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    font-size: 2rem;
    color: #333;
    margin-bottom: 30px;
}

table {
    margin-left:450px;
    width: 80%;
    max-width: 900px;
    border-collapse: collapse;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 20px;

}

th, td {
    padding: 16px;
    font-size: 16px;
    text-align: left;
}

th {
    background-color: #007bff;
    color: white;
    font-weight: 600;
    border-bottom: 3px solid #0056b3;
}

td {
    background-color: #fafafa;
    color: #333;
    border-bottom: 1px solid #ddd;
}

tr:hover td {
    background-color: #f1f3f8;
}

/* Cart Summary Section */
.cart-summary {
    text-align: center;
    margin-top: 30px;
    font-size: 1.2rem;
    font-weight: 600;
}

.cart-summary p {
    font-size: 1.5rem;
    color: #333;
    font-weight: bold;
}

/* Checkout Button */
.submit-btn {
    padding: 12px 24px;
    background-color: #28a745;
    color: white;
    font-size: 1.2rem;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 15px;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #218838;
}

/* Empty Cart Message */
.empty-cart-message {
    font-size: 1.2rem;
    color: #888;
    text-align: center;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    table {
        width: 100%;
    }

    h1 {
        font-size: 1.8rem;
    }

    .submit-btn {
        font-size: 1rem;
        padding: 10px 20px;
    }
}

@media screen and (max-width: 480px) {
    th, td {
        padding: 12px;
        font-size: 14px;
    }

    .submit-btn {
        width: 100%;
    }
}

</style>
</head>
<body>

<h1>Your Cart</h1>

<table>
    <tr>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total Price</th>
    </tr>
    <?php
    // Check if the user has any items in the cart
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['Item_name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['total_price']}</td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
    }
    ?>
</table>



</body>
</html>
