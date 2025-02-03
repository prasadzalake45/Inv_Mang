<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

include 'db_connection.php';


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
     <style>
        /* General Body Styling */
        /* General Body Styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f4f9;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    color: #333;
}

/* Form Container Styling */
form {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    padding: 40px 50px;
    width: 100%;
    max-width: 450px;
    box-sizing: border-box;
    transition: transform 0.3s ease;
}

form:hover {
    transform: translateY(-5px);
}

/* Form Heading Styling */
form h2 {
    font-size: 28px;
    text-align: center;
    color: #333;
    margin-bottom: 25px;
    font-weight: bold;
}

/* Label Styling */
form label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: #666;
}

/* Input Field Styling */
form input[type="text"],
form input[type="number"],
form select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
}

/* Input Field Focus Effect */
form input:focus,
form select:focus {
    border-color: #007bff;
    background-color: #fff;
    outline: none;
}

/* Button Styling */
form button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 14px;
    width: 100%;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    font-weight: bold;
}

/* Button Hover Effect */
form button:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

/* Success/Error Message Styling */
body p {
    margin-top: 15px;
    font-size: 15px;
    text-align: center;
}

body p.success {
    color: #28a745;
}

body p.error {
    color: #dc3545;
}

.btn {
    position: absolute;
    top: 20px; /* Adjust as needed */
    left: 20px; /* Adjust as needed */
    padding: 12px 24px;
    background-color: #007bff;
    color: white;
    text-align: center;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
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
        openModal("Items added successfully!");
      } else if (message) {
        openModal(message);
      }
    }
</script>




   
</body>
</html>