<?php
session_start();
require 'db_connection.php';

if (isset($_SESSION['userID']) && isset($_GET['recipeID'])) {
    $userID = $_SESSION['userID'];
    $recipeID = intval($_GET['recipeID']);

    // Check if recipe is already in favorites
    $checkQuery = "SELECT * FROM favourite_recipe WHERE userID = $userID AND recipeID = $recipeID";
    $checkResult = mysqli_query($connect, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // Add to favorites if not already added
        $insertQuery = "INSERT INTO favourite_recipe (userID, recipeID) VALUES ($userID, $recipeID)";
        if (mysqli_query($connect, $insertQuery)) {
            header("Location: userFavorites.php");
            exit();
        } else {
            echo "Error adding to favorites: " . mysqli_error($connect);
        }
    } else {
        header("Location: userFavorites.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
