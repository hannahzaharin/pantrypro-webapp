<html>
<head>
    <title>Update Recipe</title>
</head>
<body>
    <?php
    // Call file to connect to the server
    include("databaseconnection.php");
    ?>

    <h2>Edit Recipe Record</h2>

    <?php
    // Check for a valid recipe id via GET or POST
    if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
        $id = $_GET['id'];
    } elseif ((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
        $id = $_POST['id'];
    } else {
        echo '<p class="error">This page has been accessed in error.</p>';
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $error = array(); // Initialize an error array

        // Check for recipeTitle
        if (empty($_POST['recipeTitle'])) {
            $error[] = 'You forgot to enter the recipe title.';
        } else {
            $recipeTitle = mysqli_real_escape_string($connect, trim($_POST['recipeTitle']));
        }

        // Check for recipeDescription
        if (empty($_POST['recipeDescription'])) {
            $error[] = 'You forgot to enter the recipe description.';
        } else {
            $recipeDescription = mysqli_real_escape_string($connect, trim($_POST['recipeDescription']));
        }

        // Check for recipeCreated
        if (empty($_POST['recipeCreated'])) {
            $error[] = 'You forgot to enter the date created.';
        } else {
            $recipeCreated = mysqli_real_escape_string($connect, trim($_POST['recipeCreated']));
        }

        // Check for cookingMethod
        if (empty($_POST['cooking_method'])) {
            $error[] = 'You forgot to select a cooking method.';
        } else {
            $cookingMethod = mysqli_real_escape_string($connect, trim($_POST['cooking_method']));
        }

        if (empty($_POST['recipeCategory'])) {
            $error[] = 'You forgot to select a recipe category.';
        } else {
            $recipeCategory = mysqli_real_escape_string($connect, trim($_POST['recipeCategory']));
        }

        // If no errors, update the database
        if (empty($error)) {
            $q = "UPDATE recipes SET recipeTitle='$recipeTitle', recipeDescription='$recipeDescription', recipeCreated='$recipeCreated', cooking_method='$cookingMethod', recipeCategory='$recipeCategory' WHERE recipeID=$id";
            $result = @mysqli_query($connect, $q); // Run the query

            if (mysqli_affected_rows($connect) == 1) {
                echo '<script>alert("The recipe has been updated.");
                window.location.href="admin_dashboard.php";</script>';
            } else {
                echo '<p class="error">The recipe has not been updated due to a system error. We apologize for any inconvenience.</p>';
                echo '<p>' . mysqli_error($connect) . '<br/>Query:' . $q . '</p>';
            }
        } else {
            echo '<p class="error">The following error(s) occurred:<br/>';
            foreach ($error as $msg) {
                echo "- $msg <br/>\n";
            }
            echo '<p>Please try again.</p>';
        }
    }

    // Fetch the recipe information for the given recipe ID
    $q = "SELECT recipeTitle, recipeDescription, recipeCreated, cooking_method, recipeCategory FROM recipes WHERE recipeID=$id";
    $result = @mysqli_query($connect, $q); // Run the query

    if (mysqli_num_rows($result) == 1) {
        // Get recipe information
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // Create the form
        echo '<form action="recipeUpdate.php" method="post">
        <p><label class="label" for="recipeTitle">Title Name*:</label>
        <input type="text" id="recipeTitle" name="recipeTitle" size="30" maxlength="100" value="' . htmlspecialchars($row['recipeTitle']) . '"></p>

        <p><label class="label" for="recipeDescription">Recipe Description*:</label>
        <textarea id="recipeDescription" name="recipeDescription" rows="4" cols="50">' . htmlspecialchars($row['recipeDescription']) . '</textarea></p>

        <p><label class="label" for="recipeCreated">Date Created*:</label>
        <input type="date" id="recipeCreated" name="recipeCreated" value="' . date('Y-m-d', strtotime($row['recipeCreated'])) . '"></p>

        <p><label class="label" for="cooking_method">Cooking Method*:</label>
        <select id="cooking_method" name="cooking_method" required>
            <option value="">Select a cooking method</option>
            <option value="Baking"' . ($row['cooking_method'] == "Baking" ? ' selected' : '') . '>Baking</option>
            <option value="Grilling"' . ($row['cooking_method'] == "Grilling" ? ' selected' : '') . '>Grilling</option>
            <option value="Roasting"' . ($row['cooking_method'] == "Roasting" ? ' selected' : '') . '>Roasting</option>
            <option value="Frying"' . ($row['cooking_method'] == "Frying" ? ' selected' : '') . '>Frying</option>
            <option value="Pressure Cooking"' . ($row['cooking_method'] == "Pressure Cooking" ? ' selected' : '') . '>Pressure Cooking</option>
            <option value="Microwaving"' . ($row['cooking_method'] == "Microwaving" ? ' selected' : '') . '>Microwaving</option>
        </select></p>

        <p><label class="label" for="recipeCategory">Recipe Category*:</label>
    <select id="recipeCategory" name="recipeCategory" required>
        <option value="">Select a category</option>
       <option value="Breakfast"' . ($row['recipeCategory'] == "Breakfast" ? ' selected' : '') . '>Breakfast</option>
            <option value="Lunch"' . ($row['recipeCategory'] == "Lunch" ? ' selected' : '') . '>Lunch</option>
            <option value="Dinner"' . ($row['recipeCategory'] == "Dinner" ? ' selected' : '') . '>Dinner</option>
    </select></p>

        <p><input id="submit" type="submit" name="submit" value="Update"></p>
        <input type="hidden" name="id" value="' . $id . '"/>
    </form>';

    } else {
        // If the recipe is not found
        echo '<p class="error">This page has been accessed in error.</p>';
    }
    // Close the database connection
    mysqli_close($connect);
    ?>
</body>
</html>
