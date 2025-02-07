<?php
// Database connection


include 'db_connection.php';

// SQL query to fetch user details with orders and order details
$sql = "
SELECT 
    users.id AS user_id, 
    users.First_Name, 
    users.Last_Name, 
    users.username, 
    users.email, 
    order_details.order_no, 
    items.item_name, 
    order_details.quantity AS ordered_quantity, 
    order_details.price AS item_price, 
    (order_details.quantity * order_details.price) AS total_price,
    category.category_name
FROM users
JOIN order_details ON users.id = order_details.id
JOIN items ON order_details.item_id = items.item_id
LEFT JOIN category ON items.category_id = category.category_id
ORDER BY users.id, order_details.order_no;
";

// Execute the query and fetch results
$result = $conn->query($sql);

// HTML structure
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>User Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h3, h4 {
            color: #333;
        }
        .user-details, .order-details {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 6px;
        }
        .order-details {
            margin-top: 10px;
            background-color: #eef0f3;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-details table th, .order-details table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .order-details table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>User Order Details</h1>";

// Check if any rows are returned
if ($result->num_rows > 0) {
    $currentUserId = null;
    while($row = $result->fetch_assoc()) {
        // Display user details
        if ($currentUserId != $row['user_id']) {
            if ($currentUserId != null) {
                echo "</table></div>"; // Close previous user order table
            }
            $currentUserId = $row['user_id'];
            echo "<div class='user-details'>
                    <h3>User ID: " . $row['user_id'] . "</h3>
                    <p><strong>Name:</strong> " . $row['First_Name'] . " " . $row['Last_Name'] . "</p>
                    <p><strong>Username:</strong> " . $row['username'] . "</p>
                    
                    <h4>Orders:</h4>
                    <div class='order-details'>
                        <table>
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Price per Item</th>
                                    <th>Total Price</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>";
        }
        
        // Display order details for each item
        echo "<tr>
                <td>" . $row['order_no'] . "</td>
                <td>" . $row['item_name'] . "</td>
                <td>" . $row['ordered_quantity'] . "</td>
                <td>" . $row['item_price'] . "</td>
                <td>" . $row['total_price'] . "</td>
                <td>" . $row['category_name'] . "</td>
              </tr>";
    }
    echo "</tbody></table></div>"; // Close the last user's order table
} else {
    echo "<p>No orders found.</p>";
}

// Close the database connection
$conn->close();

echo "</div>
</body>
</html>";
?>
