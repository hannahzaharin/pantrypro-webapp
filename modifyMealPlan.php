<?php
session_start();
include('databaseconnection.php');

// Assuming user is logged in and userID is stored in the session
$userID = $_SESSION['userID']; 

// Get the meal date from URL
if (isset($_GET['date'])) {
    $mealDate = $_GET['date'];

    // Query to get the user's current meal plan for the selected date
    $query = "SELECT mealCategory, recipeID, recipeName 
              FROM meal_plans 
              JOIN recipes ON meal_plans.recipeID = recipes.recipeID 
              WHERE userID = '$userID' AND mealDate = '$mealDate'";

    $result = mysqli_query($connect, $query);

    // Display current meal plan
    echo "<h2>Modify Meal Plan for $mealDate</h2>";
    echo "<form method='POST' action='modifyMealPlan.php'>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        $mealCategory = $row['mealCategory'];
        $recipeID = $row['recipeID'];
        $recipeName = $row['recipeName'];

        echo "<p><strong>$mealCategory:</strong> $recipeName</p>";
        
        // Dropdown to select a new recipe for this category
        echo "<select name='newRecipeID_$mealCategory'>
                <option value=''>Select a new recipe</option>";

        // Fetch available recipes for this category
        $categoryQuery = "SELECT recipeID, recipeName FROM recipes WHERE recipeCategory = '$mealCategory'";
        $categoryResult = mysqli_query($connect, $categoryQuery);
        
        while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
            $newRecipeID = $categoryRow['recipeID'];
            $newRecipeName = $categoryRow['recipeName'];
            echo "<option value='$newRecipeID'>$newRecipeName</option>";
        }

        echo "</select><br><br>";
    }

    echo "<button type='submit' name='updateMealPlan'>Update Meal Plan</button>";
    echo "</form>";
} else {
    echo "No meal date selected.";
}

// Handling form submission
if (isset($_POST['updateMealPlan'])) {
    // Loop through the meal categories and update the meal plan
    foreach (['breakfast', 'lunch', 'dinner'] as $category) {
        if (isset($_POST["newRecipeID_$category"]) && $_POST["newRecipeID_$category"] != "") {
            $newRecipeID = $_POST["newRecipeID_$category"];
            // Update the meal plan for this category
            $updateQuery = "UPDATE meal_plans SET recipeID = '$newRecipeID'
                            WHERE userID = '$userID' AND mealDate = '$mealDate' AND mealCategory = '$category'";
            mysqli_query($connect, $updateQuery);
        }
    }

    // Redirect after successful update
    header("Location: viewmealPlan.php?date=$mealDate");
    exit;
}
?>
