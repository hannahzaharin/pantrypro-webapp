<?php
// Create grocery list based on selected recipe IDs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe_ids'])) {
    // Retrieve selected recipe IDs
    $selectedRecipeIDs = $_POST['recipe_ids'];
    
    // Assuming you have a user ID in the session
    $userID = $_SESSION['userID'];
    
    // Insert the selected recipe IDs into the grocery list table
    foreach ($selectedRecipeIDs as $recipeID) {
        $query = "INSERT INTO grocery_list (userID, recipeID) VALUES ('$userID', '$recipeID')";
        if (mysqli_query($connect, $query)) {
            echo "Recipe added to grocery list!";
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    }
}
?>
