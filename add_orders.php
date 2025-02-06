<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}





include 'db_connection.php';

$order_no=1;
$user_id = $_SESSION['id'];
$sql2 = "SELECT role_id FROM users WHERE id = '$user_id'";
$result1 = $conn->query($sql2);
$user = $result1->fetch_assoc();

$role_id = $user['role_id']; 
$sql = "SELECT Items.Item_id, Items.Item_name, Items.price, Items.quantity, 
        Category.Category_name 
        FROM Items 
        LEFT JOIN Category ON Items.category_id = Category.Category_id";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit_orders'])) {
    $order_details = json_decode($_POST['order_details'], true);
    $order_no = 1; // Initialize the order number

    foreach ($order_details as $order) {
        $item_id = $order['item_id'];
        $quantity = $order['quantity'];
        $price_per_item = $order['price'];
        $total_price = $quantity * $price_per_item;

        // Insert order details into the order_details table
        $stmt = $conn->prepare("INSERT INTO order_details (item_id, order_no, quantity, price, id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidi", $item_id, $order_no, $quantity, $total_price, $user_id); // Add the user_id parameter
        $stmt->execute();

        $order_no++;

        // Update Items table: subtract the ordered quantity
        $updateStmt = $conn->prepare("UPDATE Items SET quantity = quantity - ? WHERE item_id = ?");
        $updateStmt->bind_param("ii", $quantity, $item_id);
        $updateStmt->execute();

        $stmt->close();
        $updateStmt->close();
    }

    echo "<script>alert('Orders submitted successfully!');</script>";
}


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
        /* Table Styling */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

th {
    background-color: #007bff;
    color: white;
    text-align: left;
    padding: 16px;
    font-size: 16px;
    border-bottom: 3px solid #0056b3;
}

td {
    padding: 14px;
    font-size: 14px;
    color: #333;
    border-bottom: 1px solid #ddd;
}

/* Table Row Hover Effect */
tr:hover {
    background-color: #f1f3f8;
}

/* Remove Border for Last Row */
tr:last-child td {
    border-bottom: none;
}

/* Alternate Row Background for Better Readability */
tr:nth-child(even) {
    background-color: #f9fbff;
}
.order-form input[type="number"] {
            width: 50px;
            padding: 4px;
        }

        .order-form button {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .order-form button:hover {
            background-color: #218838;
        }

        .order-summary {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: #ffffff;
            border: 2px solid #007bff;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 250px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
        }

        .order-summary h3 {
            text-align: center;
            font-size: 18px;
        }

        .order-summary table {
            width: 100%;
            margin-top: 10px;
        }

        .order-summary table td {
            padding: 5px;
        }

        .submit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #218838;
        }
/* Responsive Table Design */
@media (max-width: 768px) {
    th, td {
        padding: 10px;
        font-size: 14px;
    }
}

    </style>
    <link rel="stylesheet" href="dashboard.css">
    
</head>
<body>

    
<div class="sidebar">
        <h3 style="color: #ecf0f1; text-align: center;">Inventory</h3>
        <a href="dashboard.php">Home</a>
        <?php  if($role_id==1):   ?>
        <a href="add_category.php">Add Category</a>
        
     
        <a href="add_item.php">Add Item</a>
        <a href="view_orders.php">View Orders</a>

        <?php endif; ?>
        <a href="view_inventory.php">View Inventory</a>
        <?php  if($role_id==2):   ?>
        <a href="add_orders.php">Orders</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
</div>

    <!-- Main Content -->

    <div class="main-content">

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

                <td>
    <form class="order-form" method="POST" onsubmit="event.preventDefault(); addToOrder(<?php echo $row['Item_id']; ?>, '<?php echo $row['Item_name']; ?>', <?php echo $row['price']; ?>)">
        <input type="number" name="order_quantity" min="0" max="<?php echo $row['quantity'];?>" required id="quantity-<?php echo $row['Item_id']; ?>"/>
        <input type="hidden" name="item_id" value="<?php echo $row['Item_id']; ?>"/>
        <button type="button" onclick="addToOrder(<?php echo $row['Item_id']; ?>, '<?php echo $row['Item_name']; ?>', <?php echo $row['price']; ?>)">Add</button>
    </form>
</td>

            </tr>
        <?php } ?>
    </table>


    <!-- Order Summary -->
<div class="order-summary" id="orderSummary">
    <h3>Order Summary</h3>
    <table id="orderTable"></table>
    <form method="POST">
        <input type="hidden" name="order_details" id="orderDetailsInput">
        <button type="submit" name="submit_orders" class="submit-btn">Submit Orders</button>
    </form>
</div>

<script>
let orderDetails = [];

function addToOrder(itemId, itemName, itemPrice) {
    // Get the quantity from the input field
    let quantity = document.getElementById('quantity-' + itemId).value;
    quantity = parseInt(quantity);

    // Validate the quantity
    if (quantity > 0) {
        // Check if the item already exists in the order
        let existingOrderIndex = orderDetails.findIndex(order => order.item_id === itemId);
        if (existingOrderIndex !== -1) {
            // Update the quantity if the item already exists
            orderDetails[existingOrderIndex].quantity += quantity;  // Add quantity to the existing order
            orderDetails[existingOrderIndex].total_price = orderDetails[existingOrderIndex].quantity * itemPrice;  // Update total price
        } else {
            // Add the item to the order array
            orderDetails.push({
                item_id: itemId,
                item_name: itemName,
                quantity: quantity,
                price: itemPrice,
                total_price: quantity * itemPrice  // Store total price for this item
            });
        }

        // Update the order summary
        updateOrderSummary();
    } else {
        alert('Invalid quantity.');
    }
}

function updateOrderSummary() {
    const orderTable = document.getElementById('orderTable');
    orderTable.innerHTML = ''; // Clear current order table
    let totalQuantity = 0;
    let totalPrice = 0;

    orderDetails.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `<td>${order.item_name}</td><td>${order.quantity}</td><td>${order.total_price.toFixed(2)}</td>`;
        orderTable.appendChild(row);
        
        totalQuantity += order.quantity;
        totalPrice += order.total_price;
    });

    // Display total quantity and price at the bottom of the summary
    const totalRow = document.createElement('tr');
    totalRow.innerHTML = `<td><strong>Total</strong></td><td><strong>${totalQuantity}</strong></td><td><strong>${totalPrice.toFixed(2)}</strong></td>`;
    orderTable.appendChild(totalRow);

    // Show the order summary
    document.getElementById('orderSummary').style.display = 'block';

    // Store the order details in the hidden input field as JSON
    document.getElementById('orderDetailsInput').value = JSON.stringify(orderDetails);
}

</script>
        </div>
</body>
</html>