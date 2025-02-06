<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}





include 'db_connection.php';


$user_id = $_SESSION['id'];
$sql2 = "SELECT * FROM users WHERE id = '$user_id'";
$result1 = $conn->query($sql2);
$user = $result1->fetch_assoc();

$role_id = $user['role_id']; 



$sql = "SELECT Items.Item_id, Items.Item_name, Items.price, Items.quantity, 
        Category.Category_name 
        FROM Items 
        LEFT JOIN Category ON Items.category_id = Category.Category_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>



<div class="container">
    <!-- User Details -->
    <div class="user-details">
        <div class="header">
            <h1><strong>Total Inventory</h1>
        </div>
        <!-- Profile Icon at the top right -->
        <!-- // add profile icon when i clicked it shows pop up of first_name,last_name,email and all -->
        
        <div class="profile-icon-container">
    <img src="https://th.bing.com/th?q=Best+Avatar+Profile+Icon&w=138&h=138&c=7&r=0&o=5&pid=1.7&mkt=en-IN&cc=IN&setlang=en&adlt=moderate&t=1" alt="Profile Icon" onclick="showProfileModal()" style="cursor: pointer; border-radius: 50%;">
</div>
    

</div>

    <title>View Inventory</title>

     <style>
        /* General Body Styling */
        

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
            </tr>
        <?php } ?>
    </table>
        </div>



        <div id="profileModal" class="modal">
    <div class="modal-content">
        <h3>User Profile</h3>
        <p><strong>First Name:</strong> <?php echo $user['First_Name']; ?></p>
        <p><strong>Last Name:</strong> <?php echo $user['Last_Name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <button type="button" class="cancel-btn" onclick="closeProfileModal()">OK</button>
    </div>


    <script>
    function showProfileModal() {
        document.getElementById('profileModal').style.display = 'flex'; // Show modal
    }

    function closeProfileModal() {
        document.getElementById('profileModal').style.display = 'none'; // Hide modal
    }
    
    
    
       </script>
</div>
</body>
</html>