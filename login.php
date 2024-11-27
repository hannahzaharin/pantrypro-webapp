<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
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
      max-width:700px;
      width: 100%;
      background-color: #fff;
      padding: 25px 30px;
      border-radius:10px;
      box-shadow:0 10px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow for depth */
      width: calc(100% / 2 - 300px);
    }

    .container .title {
      font-size: 28px;
      font-weight: 600;
      position: relative;
      color: #5c8a6d; /* Sage green text */
    }

    .container .title::before {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      height: 4px;
      width: 35px;
      border-radius: 5px;
      background: linear-gradient(135deg, #a8e6cf, #dcedc1); /* Green gradient */
    }

    form .input-box {
      margin-bottom: 15px;
      width: calc(100% / 2 - -120px);
    }

    form .input-box span.details {
      display: block;
      font-weight: 500;
      margin-bottom: 5px;
      color: #5c8a6d; /* Sage green text */
    }

    .input-box input {
      height: 50px;
      width: 100%;
      outline: none;
      font-size: 16px;
      border-radius: 5px;
      padding-left: 15px;
      border: 1px solid #ccc;
      border-bottom-width: 2px;
      transition: all 0.3s ease;
    }

    .input-box input:focus,
    .input-box input:valid {
        border-color: #a8e6cf; /* Change border color to match the green theme */
    }

    form .button {
      height: 50px;
      margin: 35px 0;
    }

    form .button input {
      height: 100%;
      width: 100%;
      border-radius: 5px;
      border: none;
      color: #fff;
      font-size: 18px;
      font-weight: 600;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, #a8e6cf, #dcedc1); /* Green gradient */
      width: calc(100% / 2 - -120px);
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
    .error {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
      display: center;
    }
  </style>
</head>
<body>
  <?php
  // Call file to connect to the server
  include ("databaseconnection.php");

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate userEmail
    if (!empty($_POST['userEmail'])) {
      $e = mysqli_real_escape_string($connect, $_POST['userEmail']);
    } else {
      $e = FALSE;
      echo '<p class="error">You forgot to enter your email.</p>';
    }

    // Validate userPassword
    if (!empty($_POST['userPassword'])) {
      $p = mysqli_real_escape_string($connect, $_POST['userPassword']);
    } else {
      $p = FALSE;
      echo '<p class="error">You forgot to enter your password.</p>';
    }

    // If no issues
    if ($e && $p) {
      $q = "SELECT userID, userName FROM users WHERE (userEmail='$e' AND userPassword='$p')";
      $result = mysqli_query($connect, $q);

      if (@mysqli_num_rows($result) == 1) {
        session_start();
        $_SESSION = mysqli_fetch_array($result, MYSQLI_ASSOC);
        header("Location: index.php?login=success");
        exit();
      } else {
        echo '<p class="error">Invalid email or password.</p>';
      }
    } else {
      echo '<p class="error">Please try again.</p>';
    }
    mysqli_close($connect);
  }
  ?>

  <div class="container">
    <div class="title">Member Login</div>
    <div class="content">
      <!-- Login form -->
      <form action="login.php" method="POST">
        <div class="input-box">
          <span class="details">Email</span>
          <input type="email" name="userEmail" placeholder="Enter your email" required>
        </div>
        <div class="input-box">
          <span class="details">Password</span>
          <input type="password" name="userPassword" placeholder="Enter your password" required>
        </div>
        <div class="button">
          <input type="submit" value="Login">
        </div>
        <div class="form-group">
          <label>First time here? <a href="register.php">Sign Up</a></label>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
