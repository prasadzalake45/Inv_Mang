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
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Form Container Styling */
        form {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px 40px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        /* Form Heading Styling */
        form h2 {
            font-size: 24px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Label Styling */
        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
        }

        /* Input Field Styling */
        form input[type="text"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        /* Input Field Focus Effect */
        form input:focus,
        form select:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Button Styling */
        form button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        /* Button Hover Effect */
        form button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Success/Error Message Styling */
        body p {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }

        body p.success {
            color: #28a745;
        }

        body p.error {
            color: #dc3545;
        }
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