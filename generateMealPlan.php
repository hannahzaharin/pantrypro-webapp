<?php
// Include database connection
include("databaseconnection.php");
session_start();

// Redirect to login if the user isn't logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

// Handle meal plan generation and regeneration
if (isset($_POST['generateMealPlan']) || isset($_POST['regenerateMealPlan'])) {
    // Check if the mealDate is set from the form (POST) or URL (GET)
    if (isset($_POST['mealDate'])) {
        $mealDate = $_POST['mealDate'];
    } elseif (isset($_GET['mealDate'])) {
        $mealDate = $_GET['mealDate'];
    } else {
        // If no meal date is set, redirect back to the meal planner with an error message
        header("Location: meal_planner.php?error=no_date");
        exit;
    }

    $userID = $_SESSION['userID'];
    $mealCategories = ['Breakfast', 'Lunch', 'Dinner'];

    // Clear previous temporary meal plan for the selected date
    $deleteTempQuery = "DELETE FROM temp_meal_plans WHERE userID = '$userID' AND mealDate = '$mealDate'";
    mysqli_query($connect, $deleteTempQuery);

    // Insert new random recipes for each category into the temporary meal plan table
    foreach ($mealCategories as $category) {
        // Fetch a random recipe
        $recipeQuery = "SELECT recipeID 
                        FROM recipes 
                        WHERE recipeCategory = '$category' 
                        ORDER BY RAND() LIMIT 1";
        $recipeResult = mysqli_query($connect, $recipeQuery);

        if ($recipeResult && mysqli_num_rows($recipeResult) > 0) {
            $recipe = mysqli_fetch_assoc($recipeResult);
            // Insert into temporary meal plan table
            $insertTempQuery = "INSERT INTO temp_meal_plans (userID, mealDate, mealCategory, recipeID)
                                VALUES ('$userID', '$mealDate', '$category', '" . $recipe['recipeID'] . "')";
            mysqli_query($connect, $insertTempQuery);
        }
    }

    // Redirect back to meal planner with generated meal plans and preserved mealDate
    header("Location: meal_planner.php?generated=true&mealDate=" . urlencode($mealDate));
    exit;
}
?>
