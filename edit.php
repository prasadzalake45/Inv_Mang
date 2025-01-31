<?php
include 'db_connection.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
    if ($conn->query($sql)) {
        echo "<script>
                alert('Information updated successfully!');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id=$id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Info</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-container input[type="text"],
        .form-container input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: #f8f9fa;
            box-sizing: border-box;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="email"]:focus {
            outline: none;
            border-color: #007bff;
            background-color: #e9f3fe;
        }

        .form-container button {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container button:active {
            background-color: #003f7d;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Your Information</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>