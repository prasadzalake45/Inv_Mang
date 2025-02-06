<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}


include 'db_connection.php';


// Fetch user details
$id = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id='$id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();


// Fetch all users for listing (Admin can see this)
$sql_all_users = "SELECT * FROM users WHERE role_id=2";
$users_result = $conn->query($sql_all_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="dashboard.css">
     <style>

.user-details {
    /* border: 2px solid #A0AEC0; Calm neutral border color */
    border-radius: 12px; /* Slightly smaller rounded corners */
    /* Reduced inner padding */
    background: linear-gradient(145deg, #f7fafc, #e2e8f0); /* Soft blue-gray gradient */
    margin-bottom: 30px; /* Reduced margin */
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1), 
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 style="color: #ecf0f1; text-align: center;">Inventory</h3>
        <a href="dashboard.php">Home</a>
        <?php if ($user['role_id'] == 1)  :  ?>
        <a href="add_category.php">Add Category</a>
      
        <a href="add_item.php">Add Item</a>
        <a href="view_orders.php">View Order</a>

        <?php endif; ?>
        <a href="view_inventory.php">View Inventory</a>
        <?php  if($user['role_id'] ==2):   ?>
        <a href="add_orders.php">Add Orders</a>
        <a href="userwise.php">Your Cart</a>

        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->

        <!-- User Details -->
        <div class="container">
    <!-- User Details -->
    <div class="user-details">
        <div class="header">
            <h1><strong>Welcome, <?php echo $user['username']; ?>!</strong></h1>
        </div>
        <!-- Profile Icon at the top right -->
        <!-- // add profile icon when i clicked it shows pop up of first_name,last_name,email and all -->
        
        <div class="profile-icon-container">
    <img src="https://th.bing.com/th?q=Best+Avatar+Profile+Icon&w=138&h=138&c=7&r=0&o=5&pid=1.7&mkt=en-IN&cc=IN&setlang=en&adlt=moderate&t=1" alt="Profile Icon" onclick="showProfileModal()" style="cursor: pointer; border-radius: 50%;">
</div>
    

</div>
       
       

        <!-- User Listing (Admin only) -->
        <?php if ($user['role_id'] == 1) : ?>
            <h3>All Users:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $users_result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="user-action-btn">Edit</a>
                            <!-- <form method="POST" action="delete.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="user-action-btn" style="background-color: #ff4d4d;">Delete</button>


                                
                            </form> -->

                            <button class="user-action-btn delete-btn" onclick="showModal(<?php echo $row['id']; ?>)">Delete</button>

                     

                            
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
                


    <div id="myModal" class="modal">
        <div class="modal-content">
            <h3>Are you sure you want to delete this user?</h3>
            <form id="deleteForm" method="POST" action="delete.php">
                <input type="hidden" name="id" id="userIdToDelete" value="">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="delete-btn">Delete</button>
            </form>
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

    <!-- JavaScript for Modal Handling -->
    <script>
         function showProfileModal() {
        document.getElementById('profileModal').style.display = 'flex'; // Show modal
    }

    function closeProfileModal() {
        document.getElementById('profileModal').style.display = 'none'; // Hide modal
    }

        function showModal(userId) {
            document.getElementById('userIdToDelete').value = userId; // Set user ID in hidden input
            document.getElementById('myModal').style.display = 'flex'; // Show modal
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none'; // Hide modal
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
          var modal = document.getElementById('myModal');
          if (event.target == modal) {
              closeModal();
          }
        }
    </script>
    



</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
