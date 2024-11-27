<?php
// Include database connection
include("databaseconnection.php");
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['saveMealPlan'])) {
    $mealDate = $_POST['mealDate'];
    $userID = $_SESSION['userID'];

    // Clear any previous saved meal plan for the selected date
    $deleteQuery = "DELETE FROM meal_plans WHERE userID = '$userID' AND mealDate = '$mealDate'";
    mysqli_query($connect, $deleteQuery);

    // Copy data from temp_meal_plans to meal_plans
    $insertQuery = "INSERT INTO meal_plans (userID, mealDate, mealCategory, recipeID)
                    SELECT userID, mealDate, mealCategory, recipeID
                    FROM temp_meal_plans
                    WHERE userID = '$userID' AND mealDate = '$mealDate'";
    mysqli_query($connect, $insertQuery);

    // Clear temporary meal plan after saving
    $clearTempQuery = "DELETE FROM temp_meal_plans WHERE userID = '$userID' AND mealDate = '$mealDate'";
    mysqli_query($connect, $clearTempQuery);

    header("Location: meal_planner.php?generated=true&mealDate=" . urlencode($mealDate) . "&saved=true");
    exit;
}
?>
