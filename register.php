<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Sign Up Form </title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Importing Google Fonts - Poppins */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
  background: linear-gradient(135deg, #a8e6cf, #dcedc1); /* Green gradient */
}

.container {
  max-width: 700px;
  width: 100%;
  background-color: #fff;
  padding: 25px 30px;
  border-radius: 10px; /* Softer rounded corners */
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow for depth */
}

.container .title {
  font-size: 28px; /* Increased font size for title */
  font-weight: 600; /* Increased weight for better visibility */
  position: relative;
  color: #5c8a6d; /* Sage green text */
}

.container .title::before {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  height: 4px; /* Thicker underline */
  width: 35px; /* Wider underline */
  border-radius: 5px;
  background: linear-gradient(135deg, #a8e6cf, #dcedc1); /* Green gradient */
}

.content form .user-details {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 20px 0 12px 0;
}

form .user-details .input-box {
  margin-bottom: 15px;
  width: calc(100% / 2 - 20px);
}

form .input-box span.details {
  display: block;
  font-weight: 500;
  margin-bottom: 5px;
  color: #5c8a6d; /* Sage green text */
}

.user-details .input-box input {
  height: 50px; /* Slightly taller input field */
  width: 100%;
  outline: none;
  font-size: 16px;
  border-radius: 5px;
  padding-left: 15px;
  border: 1px solid #ccc;
  border-bottom-width: 2px;
  transition: all 0.3s ease;
}

.user-details .input-box input:focus,
.user-details .input-box input:valid {
  border-color: #a8e6cf; /* Change border color to match the green theme */
}

form .button {
  height: 50px; /* Slightly taller button */
  margin: 35px 0;
}

form .button input {
  height: 100%;
  width: 100%;
  border-radius: 5px;
  border: none;
  color: #fff;
  font-size: 18px;
  font-weight: 600; /* Bold font for the button */
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.3s ease;
  background: linear-gradient(135deg, #a8e6cf, #dcedc1); /* Green gradient */
}

form .button input:hover {
  background: linear-gradient(-135deg, #dcedc1, #a8e6cf); /* Hover effect */
}

/* Responsive media query code for mobile devices */
@media(max-width: 584px) {
  .container {
    max-width: 100%;
  }

  form .user-details .input-box {
    margin-bottom: 15px;
    width: 100%;
  }

  .content form .user-details {
    max-height: 300px;
    overflow-y: scroll;
  }

  .user-details::-webkit-scrollbar {
    width: 5px;
  }
}

/* Responsive media query code for mobile devices */
@media(max-width: 459px) {
  .container .content .category {
    flex-direction: column;
  }
}
  </style>
</head>

<body>
    <?php
    // Call file to connect to the database
    include("databaseconnection.php");

    // Initialize an error array
    $error = array();

    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check for userName
        if (empty($_POST['userName'])) {
            $error[] = 'You forgot to enter your username.';
        } else {
            $n = mysqli_real_escape_string($connect, trim($_POST['userName']));
        }

        // Check for userPassword
        if (empty($_POST['userPassword'])) {
            $error[] = 'You forgot to enter your password.';
        } else {
            $p = mysqli_real_escape_string($connect, trim($_POST['userPassword']));
        }

        // Check for userEmail
        if (empty($_POST['userEmail'])) {
            $error[] = 'You forgot to enter your email.';
        } else {
            $e = mysqli_real_escape_string($connect, trim($_POST['userEmail']));
        }

        // Check for userFullName
        if (empty($_POST['userFullName'])) {
            $error[] = 'You forgot to enter your full name.';
        } else {
            $fn = mysqli_real_escape_string($connect, trim($_POST['userFullName']));
        }

        // Check for userPhoneNo
        if (empty($_POST['userPhoneNo'])) {
            $error[] = 'You forgot to enter your phone number.';
        } else {
            $ph = mysqli_real_escape_string($connect, trim($_POST['userPhoneNo']));
        }

        // Register the user in the database if there are no errors
        if (empty($error)) {
            // Prepare the query
            $q = "INSERT INTO users (userID, userName, userPassword, userEmail, userFullName, userPhoneNo) VALUES ('', '$n', '$p', '$e', '$fn', '$ph')";
            $result = @mysqli_query($connect, $q); // Run the query

            if ($result) { // If it runs
                echo '<div class="alert alert-success">Your registration is complete. Welcome to PantryPro!</div>';
                echo '<a href="login.php" class="btn btn-primary">Login</a>'; // Add login button
                exit();
            } else { // If it didn't run
                echo '<div class="alert alert-error">Error: Unable to complete the registration process.</div>';
                echo '<p>' . mysqli_error($connect) . '<br><br>Query: ' . $q . '</p>'; // Debugging Message
            }
            mysqli_close($connect); // Close the database connection
        }
    }
    ?>

  <div class="container">
    <!-- Title section -->
    <div class="title">Member Registration</div>
    <div class="content">
      <!-- Registration form -->
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="user-details">
          <!-- Input for Full Name -->
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="userFullName" placeholder="Enter your name" required>
          </div>
          <!-- Input for Username -->
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="userName" placeholder="Enter your username" required>
          </div>
          <!-- Input for Email -->
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="userEmail" placeholder="Enter your email" required>
          </div>
          <!-- Input for Phone Number -->
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="userPhoneNo" placeholder="Enter your phone number" required>
          </div>
          <!-- Input for Password -->
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="userPassword" placeholder="Enter your password" required>
          </div>
        </div>
        <!-- Submit Button -->
        <div class="button">
          <input type="submit" value="Register">
        </div>

        <div class="form-group">
                    <label>Already have an account? <a href="login.php">Log In</a></label>
                </div>
      </form>
    </div>
  </div>
</body>
</html>
