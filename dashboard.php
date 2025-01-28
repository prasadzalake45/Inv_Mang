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
        }

        .user-details {
            position: absolute;
            top: 20px;
            right: 20px;
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
        }

        .user-action-btn:hover {
            background-color: #0056b3;
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
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- User Details -->
        <div class="user-details">
            <p><strong>Welcome, <?php echo $user['username']; ?>!</strong></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
          
            
        </div>

        <!-- Dashboard Actions -->
        <!-- <div class="buttons">
            <a href="add_category.php">Add Category</a>
            <a href="add_item.php">Add Item</a>
            <a href="view_inventory.php">View Inventory</a>
        </div> -->

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
                            <form method="POST" action="delete.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="user-action-btn" style="background-color: #ff4d4d;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
