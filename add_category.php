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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Styling for the category list */
        .category-list {
            background: linear-gradient(135deg, #ffffff, #f9fafc);
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            overflow-y: auto;
            width: 400px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .category-list ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .category-list li {
            background-color: #007bff;
            color: #ffffff;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.3s ease-in-out;
        }

        .category-list li:hover {
            background-color: #0056b3;
        }

        .category-list h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 12px;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        /* Form Styling */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        form button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
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
        .button-container {
        display: flex;
        justify-content: flex-start; /* Aligns horizontally to the left */
        align-items: flex-end; /* Aligns vertically to the bottom */
        height: 100vh; /* Full height of the screen */
        position: relative;
    }

    /* Button styling */
    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-align: center;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none; /* Remove underline */
        transition: background-color 0.3s;
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
