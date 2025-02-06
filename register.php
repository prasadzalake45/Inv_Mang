<?php

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $First_Name=$_POST['First_Name'];
    $Last_Name=$_POST['Last_Name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role_name = $_POST['role'];
    



    //check email uniqueness

    $check_query = "SELECT * FROM users WHERE  email = '$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // If category already exists, display error message
        echo "<script>
                alert('Email already exists!');
                window.history.back();
              </script>";
        exit;
    }

       // Check username uniqueness
       $check_username = $conn->prepare("SELECT username FROM users WHERE username = ?");
       $check_username->bind_param("s", $username);
       $check_username->execute();
       $check_username->store_result();
       

    


       if ($check_username->num_rows > 0) {
           echo "<script>
                   alert('Username already exists!');
                   window.history.back();
                 </script>";
           exit;
       }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match. Please try again.');
                window.history.back();
              </script>";
        exit;
    }


        // Validate email format
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
            echo "<script>
                    alert('Invalid email format! Must be @gmail.com');
                    window.history.back();
                  </script>";
            exit;
        }



        $role_query = $conn->prepare("SELECT role_id FROM role WHERE role_name = ?");
        $role_query->bind_param("s", $role_name);
        $role_query->execute();
        $role_query->store_result();
        $role_query->bind_result($role_id);
        $role_query->fetch();
    
        if (!$role_id) {
            echo "<script>
                    alert('Invalid role selected.');
                    window.history.back();
                  </script>";
            exit;
        }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Encrypt password

    $sql = "INSERT INTO users (username,First_Name,Last_Name, email, password, role_id) VALUES ('$username','$First_Name','$Last_Name', '$email', '$hashed_password', '$role_id')";
    if ($conn->query($sql)) {
        echo "<script>
                alert('User successfully registered! You can now log in.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- Registration Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-container h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #66afe9;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .error{
            color:red;
        }

        form label {
    display: block;
    font-weight: 600;
    margin-bottom: 10px;
    color: #666;
}

/* Dropdown Styling */
form select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: border-color 0.3s ease;
    margin-bottom: 20px;
    cursor: pointer;
}

/* Focus Effect */
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
    </style>
    
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form method="POST" onsubmit="return validateForm()">
            <div>
            <input type="text" name="username" id="username"  placeholder="Enter Username" required>
            <span id="usernameError" class="error"></span>
          </div>


          <div>
            <input type="text" id="First_Name" name="First_Name" placeholder="Enter First Name" required>

           
           </div>


           <div>
            <input type="text" id="Last_Name" name="Last_Name" placeholder="Enter Last Name" required>

            
           </div>





             <div>
            <input type="email" id="email" name="email" placeholder="Enter Email" required>

            <span id="emailError" class="error"></span>
           </div>

           <div>
            <input type="password" id="password" name="password" placeholder="Enter Password" required>
            <span id="passwordError" class="error"></span>

    </div>

          <div>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <span class="confirm_passwordError" class="error"></span>
    </div>
           
           
    <label for="role">Role:</label>
<select name="role" required>
    <option value="admin">admin</option>
    <option value="user">user</option>
</select>


            <button type="submit">Register</button>
        </form>
        <div class="form-footer">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    <script>
        function validateForm() {
            // Get values
            const username = document.getElementById("username").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const confirmPassword = document.getElementById("confirm_password").value.trim();

            // Clear previous errors
            document.querySelectorAll('.error').forEach(e => e.textContent = '');

            // Username validation
            const usernameRegex = /^[a-zA-Z][a-zA-Z0-9!@#$%^&*]{3,}$/;
            if (!username) {
                showError('usernameError', 'Username is required');
                return false;
            }
            if (/^\d/.test(username)) {
                showError('usernameError', 'Username Cannot start with number');
                return false;
            }
            if (!usernameRegex.test(username)) {
                showError('usernameError', 'Must include letters, numbers & one symbol');
                return false;
            }

            // Email validation
            const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
            if (!emailRegex.test(email)) {
                showError('emailError', 'Invalid email (must be @gmail.com)');
                return false;
            }

            // Password validation
            if (password.length < 8 || password.length > 20) {
                showError('passwordError', 'Password must be 8-20 characters');
                return false;
            }

            // Confirm password
            if (password !== confirmPassword) {
                showError('confirmPasswordError', 'Passwords do not match');
                return false;
            }

            return true;
        }

        function showError(elementId, message) {
            document.getElementById(elementId).textContent = message;
        }
    </script>

</body>
</html>
