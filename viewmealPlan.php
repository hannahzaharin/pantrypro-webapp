<?php
session_start(); // Start session to use session variables
include('databaseconnection.php'); // Include database connection

// Get the meal date from URL parameters
if (isset($_GET['date'])) {
    $mealDate = $_GET['date'];
    $userID = $_SESSION['userID']; // Assuming userID is stored in the session

    // Query to get the meal plan for the specific date and user
    $query = "SELECT mealCategory, recipeName 
              FROM meal_plans 
              JOIN recipes ON meal_plans.recipeID = recipes.recipeID 
              WHERE userID = '$userID' AND mealDate = '$mealDate'";

    $result = mysqli_query($connect, $query);

    echo "<h2>Meal Plan for $mealDate</h2>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>{$row['mealCategory']}: {$row['recipeName']}</p>";
    }
} else {
    echo "No meal date selected.";
}
?>
