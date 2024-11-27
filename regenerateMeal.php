<?php
include("databaseconnection.php");
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'] ?? '';
    $mealDate = $_POST['mealDate'] ?? '';
    $userID = $_SESSION['userID'];

    if (empty($category) || empty($mealDate)) {
        echo json_encode(['error' => 'Invalid parameters']);
        exit;
    }

    // Select a random recipe for the given category
    $query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage
              FROM recipes
              WHERE category = '$category'
              ORDER BY RAND() LIMIT 1";

    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $recipe = mysqli_fetch_assoc($result);

        // Update the meal plan with the new recipe
        $updateQuery = "UPDATE meal_plans 
                        SET recipeID = '" . $recipe['recipeID'] . "' 
                        WHERE userID = '$userID' 
                        AND mealDate = '$mealDate' 
                        AND mealCategory = '$category'";
        mysqli_query($connect, $updateQuery);

        // Return the new recipe details as JSON
        echo json_encode([
            'recipeID' => $recipe['recipeID'],
            'recipeTitle' => $recipe['recipeTitle'],
            'recipeDescription' => $recipe['recipeDescription'],
            'recipeImage' => $recipe['recipeImage']
        ]);
    } else {
        echo json_encode(['error' => 'No recipe found for this category']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
