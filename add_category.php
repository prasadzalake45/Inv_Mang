<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $conn->real_escape_string($_POST['category_name']);

    $sql = "INSERT INTO Category (Category_name) VALUES ('$category_name')";
    if ($conn->query($sql) === TRUE) {
        $message = "Category added successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch categories for the list
$category_query = "SELECT Category_name FROM Category"; 
$categories = $conn->query($category_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <style>
        /* Reset default margins and paddings */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Main container for content */
        .container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        button:active {
            background-color: #003f7d;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #e7f3e7;
            color: #4caf50;
            border-radius: 5px;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da;
            color: #dc3545;
        }

        .category-list {
            background-color: #f9fafc;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-top: 20px;
            width: 100%;
            max-width: 450px;
        }

        .category-list h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 12px;
            text-align: center;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }

        li {
            background-color: #007bff;
            color: white;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
            transition: background-color 0.3s ease-in-out;
        }

        li:hover {
            background-color: #0056b3;
        }

        .btn {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn:active {
            background-color: #003f7d;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="btn">Go to Target Page</a>

<form method="POST">
    <label for="category_name">Category Name:</label>
    <input type="text" name="category_name" required>
    <button type="submit">Add Category</button>
</form>

<?php if (isset($message)) { ?>
    <div id="message-box" class="message<?php echo strpos($message, 'Error') === false ? '' : ' error'; ?>">
        <?php echo $message; ?>
    </div>

    <script>
        // Hide the message after 2 seconds
        setTimeout(function() {
            var messageBox = document.getElementById('message-box');
            if (messageBox) {
                messageBox.style.display = 'none';
            }
        }, 2000); // 2000ms = 2 seconds
    </script>
<?php } ?>

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

</body>
</html>
