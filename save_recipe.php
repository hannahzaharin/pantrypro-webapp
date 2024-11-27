<?php
include('databaseconnection.php');
session_start();

if (isset($_POST['recipeID']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $recipeID = $_POST['recipeID'];
    
    // Check if the recipe is already saved
    $checkSavedQuery = "SELECT * FROM saved_recipes WHERE userID = '$userID' AND recipeID = '$recipeID'";
    $checkSavedResult = mysqli_query($connect, $checkSavedQuery);
    
    if (mysqli_num_rows($checkSavedResult) > 0) {
        // Remove from favorites if already saved
        $deleteQuery = "DELETE FROM saved_recipes WHERE userID = '$userID' AND recipeID = '$recipeID'";
        mysqli_query($connect, $deleteQuery);
        echo "Recipe removed from favorites.";
    } else {
        // Add to favorites
        $insertQuery = "INSERT INTO saved_recipes (userID, recipeID) VALUES ('$userID', '$recipeID')";
        mysqli_query($connect, $insertQuery);
        echo "Recipe added to favorites.";
    }
} else {
    echo "Error: Missing parameters or user not logged in.";
}
?>
