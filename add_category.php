<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';
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
            /* background-color: #007bff; */
            color: black;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
            transition: background-color 0.3s ease-in-out;
            font-weight:bold;
        }

        /* li:hover {
            background-color: #0056b3;
        } */

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

        #message-box{
            color:red;
        }

        .modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8); /* Darker backdrop for better contrast */
  justify-content: center;
  align-items: center;
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Modal content */
.modal-content {
  background: linear-gradient(135deg, #ffffff, #f0f4f9); /* Gradient for a modern look */
  padding: 40px;
  border-radius: 16px;
  text-align: center;
  width: 360px;
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
  transform: scale(0.9);
  animation: popIn 0.3s ease-out forwards;
}

@keyframes popIn {
  to {
    transform: scale(1);
  }
}

/* Heading */
.modal-content h3 {
  color: #333;
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 16px;
}

/* Buttons */
.open-btn,
.close-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px 18px;
  cursor: pointer;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  transition: background-color 0.3s, transform 0.2s;
}

.open-btn:hover,
.close-btn:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
}

.open-btn:active,
.close-btn:active {
  background-color: #003f7d;
  transform: translateY(1px);
}

/* Spacing between elements */
.modal-content p {
  margin-bottom: 20px;
}

    </style>
</head>
<body>

<a href="dashboard.php" class="btn">Go to Target Page</a>

<form method="POST">
    <label for="category_name">Category Name:</label>
    <input type="text" name="category_name" required>
    <button type="submit" >Add Category</button>
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

<div id="simpleModal" class="modal">
    <div class="modal-content">
      <h3 id="modal-message">Category Added Successfully!</h3>
      <button class="close-btn" onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
    // Open Modal
    function openModal(message) {
      document.getElementById('simpleModal').style.display = 'flex';
      document.getElementById('modal-message').innerText = message;
    }

    // Close Modal
    function closeModal() {
      document.getElementById('simpleModal').style.display = 'none';
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
