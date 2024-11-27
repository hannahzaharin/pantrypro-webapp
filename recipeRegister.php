<?php
// Call file to connect to the database
include("databaseconnection.php");

// Check if the user is logged in as admin (optional)
// Implement your admin check here if needed

// Has the form been submitted?
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = array(); // Initialize an error array

    // Check for recipeTitle
    if (empty($_POST['recipeTitle'])) {
        $error[] = 'You forgot to enter the recipe title.';
    } else {
        $t = mysqli_real_escape_string($connect, trim($_POST['recipeTitle']));
    }

    // Check for recipeDescription
    if (empty($_POST['recipeDescription'])) {
        $error[] = 'You forgot to enter the recipe description.';
    } else {
        $d = mysqli_real_escape_string($connect, trim($_POST['recipeDescription']));
    }

    // Check for recipeImage
    if (empty($_FILES['recipeImage']['name'])) {
        $error[] = 'You forgot to enter the recipe image.';
    } else {
        $img = $_FILES['recipeImage']['name'];
        $target = "uploads/" . basename($img); // Set the target directory for uploads
    }

    // Check for recipeCreated
    if (empty($_POST['recipeCreated'])) {
        $error[] = 'You forgot to enter the date.';
    } else {
        $ct = mysqli_real_escape_string($connect, trim($_POST['recipeCreated']));
    }

    // Check for cooking_method
    if (empty($_POST['cooking_method'])) {
        $error[] = 'You forgot to select a cooking method.';
    } else {
        $cm = mysqli_real_escape_string($connect, trim($_POST['cooking_method']));
    }

    if (empty($_POST['recipeCategory'])) {
        $error[] = 'You forgot to select a recipe category.';
    } else {
        $category = mysqli_real_escape_string($connect, trim($_POST['recipeCategory']));
    }
    
    // Check if the uploads directory exists, if not, create it
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true); // Creates the directory with write permissions
    }

    // Attempt to move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['recipeImage']['tmp_name'], $target)) {
        // Register the recipe in the database
        $q = "INSERT INTO recipes (recipeID, recipeTitle, recipeDescription, recipeImage, recipeCreated, cooking_method, recipeCategory) VALUES ('', '$t', '$d', '$img', '$ct', '$cm', '$category')";
        $result = mysqli_query($connect, $q);

        if ($result) { // If it runs
            // Get the ID of the inserted recipe and redirect to add_ingredient.php
            $recipeID = mysqli_insert_id($connect);
            header("Location: add_ingredient.php?recipeID=" . $recipeID);
            exit();
        } else {
            echo '<h1>Error: Unable to complete the registration process.</h1>';
            echo '<p>' . mysqli_error($connect) . '<br><br>Query: ' . $q . '</p>';
        }
    } else {
        echo '<h1>Error: Unable to upload the image.</h1>';
    }
}

mysqli_close($connect); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Input</title>
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

        .user-details .input-box input,
        .user-details .input-box select {
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
        .user-details .input-box input:valid,
        .user-details .input-box select:focus {
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
    <div class="container">
        <!-- Title section -->
        <div class="title">Recipe Input</div>
        <div class="content">
            <!-- Registration form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="user-details">
                    <!-- Input for Recipe Title -->
                    <div class="input-box">
                        <span class="details">Recipe Title</span>
                        <input type="text" name="recipeTitle" placeholder="Enter recipe title" required>
                    </div>
                    <!-- Input for Recipe Description -->
                    <div class="input-box">
                        <span class="details">Recipe Description</span>
                        <input type="text" name="recipeDescription" placeholder="Enter recipe description" required>
                    </div>
                    <!-- Recipe Image -->
                    <div class="input-box">
                        <label for="recipeImage">Recipe Image*:</label>
                        <input type="file" id="recipeImage" name="recipeImage" accept="image/*" required>
                        <small id="imageHelp" class="form-text text-muted">Please upload an image file (JPG, PNG, GIF).</small>
                    </div>
                    <!-- Input for Date Created -->
                    <div class="input-box">
                        <span class="details">Date Created</span>
                        <input type="date" name="recipeCreated" required>
                    </div>
                    <!-- Dropdown for Cooking Method -->
                    <div class="input-box">
                        <span class="details">Cooking Method</span>
                        <select name="cooking_method" required>
                            <option value="">Select a cooking method</option>
                            <option value="Baking">Baking</option>
                            <option value="Grilling">Grilling</option>
                            <option value="Roasting">Roasting</option>
                            <option value="Frying">Frying</option>
                            <option value="Pressure Cooking">Pressure Cooking</option>
                            <option value="Microwaving">Microwaving</option>
                        </select>
                    </div>
                    <!-- Dropdown for Recipe Category -->
<div class="input-box">
    <span class="details">Recipe Category</span>
    <select name="recipeCategory" required>
        <option value="">Select a category</option>
        <option value="Breakfast">Breakfast</option>
        <option value="Lunch">Lunch</option>
        <option value="Dinner">Dinner</option>
    </select>
</div>

                </div>
                <div class="button">
                    <input type="submit" value="Register Recipe">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
