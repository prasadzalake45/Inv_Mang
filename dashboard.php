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
$sql_all_users = "SELECT * FROM users";
$users_result = $conn->query($sql_all_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 250px;
    background-color: #2c3e50;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    border-right: 2px solid #34495e;
}

.sidebar a {
    display: block;
    padding: 15px;
    color: white;
    text-decoration: none;
    font-size: 18px;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background-color: #34495e;
}

.container {
    margin-left: 270px; /* To accommodate the sidebar */
    width: calc(100% - 270px);
    padding: 30px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    position: relative;
    transition: margin-left 0.3s ease;
}



.header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 20px;
}

.header p {
    font-size: 20px;
    color: #333;
    margin-right: 20px;
    margin-bottom: 10px;
}

.user-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    background-color: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.view-info-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
    align-self: flex-end; /* Align the button to the left */
    margin-top: 10px; /* Add space between the info and the button */
}

.view-info-btn:hover {
    background-color: #0056b3;
}

.user-info {
    display: none;
    margin-top: 10px;
    font-size: 16px;
    color: #555;
}


.logout-btn {
    background-color: #ff4d4d;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.logout-btn:hover {
    background-color: #e60000;
}

h2 {
    font-size: 24px;
    margin-bottom: 15px;
    color: #333;
    font-weight: bold;
}

p {
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
}

.buttons {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-top: 30px;
}

.buttons a {
    background-color: #007bff;
    color: white;
    padding: 15px 0;
    text-align: center;
    border-radius: 5px;
    font-size: 18px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.buttons a:hover {
    background-color: #0056b3;
}

.buttons a:active {
    background-color: #003f7d;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 100%;
        margin-left: 0;
        padding: 20px;
    }

    .buttons {
        grid-template-columns: 1fr;
    }

    .sidebar {
        width: 200px;
    }
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
    font-weight: bold;
}

td {
    background-color: #f9f9f9;
}

.user-action-btn {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-right: 5px;
    transition: background-color 0.3s;
}

.user-action-btn:hover {
    background-color: #0056b3;
}

.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    background-color: rgba(0, 0, 0, 0.4); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fefefe;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
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
    background-color: #dc3545; /* Red */
    color: white;
}

.delete-btn:hover {
    background-color: #c82333; /* Darker red */
}

.cancel-btn {
    background-color: #6c757d; /* Gray */
    color: white;
}

.cancel-btn:hover {
    background-color: #5a6268; /* Darker gray */
}
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 style="color: #ecf0f1; text-align: center;">Dashboard</h3>
        <a href="dashboard.php">Home</a>
        <a href="add_category.php">Add Category</a>
        <a href="add_item.php">Add Item</a>
        <a href="view_inventory.php">View Inventory</a>
        <a href="add_orders.php">Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- User Details -->
        <div class="container">
    <!-- User Details -->
    <div class="user-details">
        <div class="header">
            <p><strong>Welcome, <?php echo $user['username']; ?>!</strong></p>
            
            <p>Role :<?php echo $user['role']; ?></p>
            
        </div>
        <button class="view-info-btn" onclick="toggleUserInfo()">View Info</button>
        <div id="userInfo" class="user-info" style="display: none;">
            <p><strong>First Name:</strong> <?php echo $user['First_Name']; ?></p>
            <p><strong>Last Name:</strong> <?php echo $user['Last_Name']; ?></p>
        </div>
    </div>
</div>
        
       

        <!-- User Listing (Admin only) -->
        <?php if ($user['role'] == 'admin') : ?>
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

    <!-- JavaScript for Modal Handling -->
    <script>

function toggleUserInfo() {
    var userInfo = document.getElementById('userInfo');
    if (userInfo.style.display === "none") {
        userInfo.style.display = "block";
    } else {
        userInfo.style.display = "none";
    }
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
