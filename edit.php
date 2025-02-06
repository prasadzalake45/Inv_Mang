<?php
include 'db_connection.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $First_Name = $_POST['First_Name'];
    $Last_Name = $_POST['Last_Name'];

    $sql = "UPDATE users SET First_Name='$First_Name', Last_Name='$Last_Name' WHERE id=$id";
    if ($conn->query($sql)) {
        $message = "success";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM users WHERE id=$id";
        $result = $conn->query($sql);

        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            // If no user found, redirect or show an error
            header("Location: dashboard.php");
            exit();
        }
    }

    // If $user is not set, prevent form from displaying
    if (!isset($user)) {
        header("Location: dashboard.php");
        exit();
    }

    if (isset($message) && $message == 'success') {
        $message = "success";
    }
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
     <a href="dashboard.php" class="btn">Go to Target Page</a>
    <div class="form-container">
        <h2>Update Your Information</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo isset($user) ? $user['id'] : ''; ?>">
            <input type="text" name="First_Name" value="<?php echo isset($user) ? $user['First_Name'] : ''; ?>" required>
            <input type="text" name="Last_Name" value="<?php echo isset($user) ? $user['Last_Name'] : ''; ?>" required>
            <button type="submit">Update</button>
        </form>


      <div id="simpleModal" class="modal">
            <div class="modal-content">
                <h3 id="modal-message">Items Added Successfully!</h3>
                <button class="close-btn" onclick="closeModal()">OK</button>
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
                window.location.href="dashboard.php";
            }

            // Trigger modal based on PHP result
            window.onload = function () {
                var message = "<?php echo $message; ?>";
                if (message === "success") {
                    openModal("User Updated successfully!");
                } else if (message) {
                    openModal(message);
                }
            }
        </script>

        
    </div>
</body>
</html>