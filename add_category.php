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

$message="";





if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $conn->real_escape_string($_POST['category_name']);

    $check_query = "SELECT * FROM Category WHERE Category_name = '$category_name'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // If category already exists, display error message
        $message = "Duplicate entry: Category already exists.";
    } else {
        // If category doesn't exist, insert it
        $sql = "INSERT INTO Category (Category_name) VALUES ('$category_name')";
        if ($conn->query($sql) === TRUE) {
             $message = "success";
            

        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Fetch categories for the list
$category_query = "SELECT Category_name FROM Category order by category_id ASC"; 
$categories = $conn->query($category_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
 
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="add_category.css">
    
    <style>

        

    /* Existing styles */
    .user-details {
    /* Increased width */
   height:55px;
    
    background: linear-gradient(145deg, #f7fafc, #e2e8f0); /* Soft blue-gray gradient */
    margin-bottom: 30px; /* Reduced margin */
    box-shadow: 2px 2px 18px rgba(0, 0, 0, 0.1), 
                -2px -2px 8px rgba(255, 255, 255, 0.7); /* Smaller shadow */
    display: flex;
    align-items: center;
    gap: 10px; /* Reduced spacing between children */
    font-family: 'Poppins', sans-serif;
    font-size: 14px; /* Reduced font size */
    
    transition: all 0.3s ease-in-out;
}


        </style>
</head>
<body>
<div class="container">
    <!-- User Details -->
    <div class="user-details">
        <div class="header">
            <h1><strong>Add Categories Here</strong></h1>
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
        <a href="view_orders.php">View Orders</a>
        <?php endif; ?>
        <a href="view_inventory.php">View Inventory</a>
        <?php  if($role_id==2):   ?>
        <a href="add_orders.php">Orders</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
    
       
  

    <?php if ($role_id == 1): ?>
        <form method="POST">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" required>
            <button type="submit">Add Category</button>
        </form>
   
    
    <!-- Display Categories in the corner -->
    <div class="category-list">
        <h3>Category List</h3>
        <ul>
            <?php
            if ($categories->num_rows > 0) {
                while ($row = $categories->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row['Category_name']) . "</li>";
                }
            } else {
                echo "<li>No categories found.</li>";
            }
            ?>
        </ul>
    </div>
    <?php endif; ?>

<div id="simpleModal" class="modal">
    <div class="modal-content">
      <h3 id="modal-message">Category Added Successfully!</h3>
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
      window.location.href = "dashboard.php";
    }

    // Trigger modal based on PHP result
    window.onload = function () {
      var message = "<?php echo $message; ?>";
      if (message === "success") {
        openModal("Category added successfully!");
       
      } else if (message) {
        openModal(message);
      }
    }
</script>





</body>
</html>
