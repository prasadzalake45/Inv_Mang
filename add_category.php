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
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Center container */
        .container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }

        /* Form Styling */
        form {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        form input[type="text"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        form button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out; /* Added transform for effect */
        }

        form button:hover {
            background-color: #0056b3; /* Darker blue */
            transform: translateY(-2px); /* Lift effect */
        }

        /* Message styling */
        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #e7f3e7; /* Light green */
            color: #4caf50; /* Green text */
            border-radius: 5px;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da; /* Light red */
            color: #dc3545; /* Red text */
        }

        /* Category list styling */
        .category-list {
            background-color: #f9fafc; /* Light gray */
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            max-height: 300px; /* Set a max height for scrolling */
            overflow-y: auto; /* Enable scrolling */
        }

        .category-list h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 12px;
            text-align: center;
        }

        .category-list ul {
            list-style-type: none; /* Remove bullet points */
            padding-left: 0; /* Remove padding on left */
        }

        .category-list li {
            background-color: #007bff; /* Blue background for category items */
            color: white; /* White text color */
            padding: 10px; /* Padding for items */
            margin-bottom: 8px; /* Space between items */
            border-radius: 6px; /* Rounded corners for items */
            transition: background-color 0.3s ease-in-out; /* Smooth transition effect */
        }

        .category-list li:hover {
           background-color:#0056b3; /* Darker blue on hover */
       }
       
       /* Button styling for navigation back to dashboard */
       .btn {
           display:inline-block; 
           padding :10 px20 px ; 
           background-color:#007bff ; 
           color:white ; 
           text-align:center ; 
           border:none ; 
           border-radius :5 px ; 
           cursor:pointer ; 
           font-size :16 px ; 
           text-decoration:none ; 
           transition :background-color0.3 s ;
       }
       
       .btn:hover{
           background-color:#0056b3 ;
       }
       
       .btn :active{
           background-color:#003f7d ;
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
