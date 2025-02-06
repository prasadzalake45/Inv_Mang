<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';

$user_id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$role_id = $user['role_id']; 


// Fetch categories for dropdown
$categories = $conn->query("SELECT * FROM Category");
$message="";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $category_id = $conn->real_escape_string($_POST['category_id']);

    $check_query = "SELECT * FROM Items WHERE  item_name = '$item_name'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // If category already exists, display error message
        $message = "Duplicate entry: Items already exists.";
    }

    else{

        $sql = "INSERT INTO Items (Item_name, price, quantity, category_id) 
            VALUES ('$item_name', '$price', '$quantity', '$category_id')";
    if ($conn->query($sql) === TRUE) {
        $message = "success";
       
    } else {
        $message = "Error: " . $conn->error;
    }

    }

    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="add_items.css">
    
    <style>

/* Existing styles */
.user-details {
/* Existing styles */
border-radius: 12px;

background: linear-gradient(145deg, #f7fafc, #e2e8f0);
margin-bottom: 50px;
box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1), 
            -2px -2px 8px rgba(255, 255, 255, 0.7);
display: flex;
gap: 10px;
width:85%;
font-family: 'Poppins', sans-serif;
font-size: 12px;
transition: all 0.3s ease-in-out;

/* New styles for positioning */
position: fixed; 
top: 20px; 
left: 280px; 
z-index: 1000; 

}



    </style>
</head>
<body>


<div class="container">
    <!-- User Details -->
    <div class="user-details">
        <div class="header">
            <h1><strong>Add Items Here</strong></h1>
        </div>
        <!-- Profile Icon at the top right -->
        <!-- // add profile icon when i clicked it shows pop up of first_name,last_name,email and all -->
        
        <div class="profile-icon-container">
    <img src="https://th.bing.com/th?q=Best+Avatar+Profile+Icon&w=138&h=138&c=7&r=0&o=5&pid=1.7&mkt=en-IN&cc=IN&setlang=en&adlt=moderate&t=1" alt="Profile Icon" onclick="showProfileModal()" style="cursor: pointer; border-radius: 50%;">
</div>
    

</div>

   

<div class="sidebar">
        <h3 style="color: #ecf0f1; text-align: center;">Inventory</h3>
        <a href="dashboard.php">Home</a>
        <?php  if($role_id==1):   ?>
        <a href="add_category.php">Add Category</a>
        
        <a href="add_item.php">Add Item</a>
        <a href="add_item.php">View Orders</a>

        <?php endif; ?>
        <a href="view_inventory.php">View Inventory</a>
        <?php  if($role_id==2):   ?>
        <a href="add_orders.php"> Add Orders</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
    <form method="POST">
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" required>
        <label for="price">Price:</label>
        <input type="number" name="price" required>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>
        <label for="category_id">Category:</label>

       




        <select name="category_id">
            <?php while ($row = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $row['Category_id']; ?>">
                    <?php echo $row['Category_name']; ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Add Item</button>
    </form>


    <div id="simpleModal" class="modal">
    <div class="modal-content">
      <h3 id="modal-message">Items Added Successfully!</h3>
      <button class="close-btn" onclick="closeModal()">Ok</button>
    </div>
</div>


<div id="profileModal" class="modal">
    <div class="modal-content">
        <h3>User Profile</h3>
        <p><strong>First Name:</strong> <?php echo $user['First_Name']; ?></p>
        <p><strong>Last Name:</strong> <?php echo $user['Last_Name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <button type="button" class="cancel-btn" onclick="closeProfileModal()">OK</button>
    </div>
</div>

<script>

function showProfileModal() {
        document.getElementById('profileModal').style.display = 'flex'; // Show modal
    }

    function closeProfileModal() {
        document.getElementById('profileModal').style.display = 'none'; // Hide modal
    }
    // Open Modal
    function openModal(message) {
      document.getElementById('simpleModal').style.display = 'flex';
      document.getElementById('modal-message').innerText = message;
    }

    // Close Modal
    function closeModal() {
      document.getElementById('simpleModal').style.display = 'none';
      window.location.href = "view_inventory.php";
    }

    // Trigger modal based on PHP result
    window.onload = function () {
      var message = "<?php echo $message; ?>";
      if (message === "success") {
        openModal("Items added successfully!");
      } else if (message) {
        openModal(message);
      }
    }
</script>




   
</body>
</html>