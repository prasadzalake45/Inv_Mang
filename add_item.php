<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
include 'db_connection.php';

// Fetch categories for dropdown
$categories = $conn->query("SELECT * FROM Category");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $category_id = $conn->real_escape_string($_POST['category_id']);

    $sql = "INSERT INTO Items (Item_name, price, quantity, category_id) 
            VALUES ('$item_name', '$price', '$quantity', '$category_id')";
    if ($conn->query($sql) === TRUE) {
        $message = "Item added successfully.";
    } else {
        $message = "Error: " . $conn->error;
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
    <?php if (isset($message)) echo $message; ?>
</body>
</html>