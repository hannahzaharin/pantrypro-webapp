<?php
// Include database connection
include("databaseconnection.php");

// Check if a recipeID is provided in the URL
if (isset($_GET['recipeID'])) {
    $recipeID = $_GET['recipeID'];

    // Delete related records in the correct order due to foreign key constraints

    // 1. Delete records in `recipeviews`
    $deleteViewsQuery = "DELETE FROM recipeviews WHERE recipeID = '$recipeID'";
    if (!mysqli_query($connect, $deleteViewsQuery)) {
        echo "Error deleting recipe views: " . mysqli_error($connect);
    }

    // 2. Delete records in `recipe_ratings`
    $deleteRatingsQuery = "DELETE FROM recipe_ratings WHERE recipeID = '$recipeID'";
    if (!mysqli_query($connect, $deleteRatingsQuery)) {
        echo "Error deleting recipe ratings: " . mysqli_error($connect);
    }

    // 3. Delete records in `saved_recipes`
    $deleteSavedRecipesQuery = "DELETE FROM saved_recipes WHERE recipeID = '$recipeID'";
    if (!mysqli_query($connect, $deleteSavedRecipesQuery)) {
        echo "Error deleting saved recipes: " . mysqli_error($connect);
    }

    // 4. Delete records in `recipe_ingredients`
    $deleteIngredientsQuery = "DELETE FROM recipe_ingredients WHERE recipeID = '$recipeID'";
    if (!mysqli_query($connect, $deleteIngredientsQuery)) {
        echo "Error deleting recipe ingredients: " . mysqli_error($connect);
    }

    // 5. Delete records in `tutorial_steps`
    $deleteStepsQuery = "DELETE FROM tutorial_steps WHERE recipeID = '$recipeID'";
    if (!mysqli_query($connect, $deleteStepsQuery)) {
        echo "Error deleting tutorial steps: " . mysqli_error($connect);
    }

    // Finally, delete the recipe itself
    $deleteRecipeQuery = "DELETE FROM recipes WHERE recipeID = '$recipeID'";
    if (mysqli_query($connect, $deleteRecipeQuery)) {
        // Redirect to admin_dashboard.php with a success flag
        header("Location: admin_dashboard.php?deleted=true");
        exit;
    } else {
        // Display an error message if the recipe could not be deleted
        echo "Error deleting recipe: " . mysqli_error($connect);
    }
} else {
    // If no recipeID is provided, display an error message
    echo "Error: Recipe ID not provided.";
}

// Close the database connection
mysqli_close($connect);
?>
