

<?php
// Include the database connection
include 'databaseconnection.php';

// Fetch the ingredient from the URL
$ingredientName = isset($_GET['ingredient']) ? $_GET['ingredient'] : '';

// Fetch recipes based on the selected ingredient
if ($ingredientName) {
    // Using MySQLi for querying
    $ingredientName = mysqli_real_escape_string($connect, $ingredientName); // Prevent SQL Injection
    $query = "SELECT r.recipeID, r.recipeTitle, r.recipeImage
              FROM recipes r
              JOIN recipe_ingredients ri ON r.recipeID = ri.recipeID
              JOIN ingredients i ON ri.ingredientID = i.ingredientID
              WHERE i.ingredientName LIKE '%$ingredientName%'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $recipes = mysqli_fetch_all($result, MYSQLI_ASSOC); // Fetch recipes
    } else {
        echo "Error: " . mysqli_error($connect); // Handle query errors
        $recipes = [];
    }
} else {
    $recipes = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes with <?= htmlspecialchars($ingredientName); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
    /* General Styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f3f4f6;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

/* Header Styling */
h2 {
    font-size: 32px;
    color: #e63946;
    text-align: center;
    margin-top: 20px;
    font-weight: 600;
}

/* Recipe List Container */
.container {
    max-width: 1200px;
    width: 100%;
    padding: 20px;
}

/* Styling the Recipe Cards */
.recipes-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-top: 30px;
}

/* Recipe Card */
.recipe-item {
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    width: 250px;
    cursor: pointer;
    text-align: center;
}

.recipe-item:hover {
    transform: translateY(-10px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
}

.recipe-item img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-bottom: 2px solid #f1f1f1;
}

.recipe-item h3 {
    font-size: 20px;
    color: #333;
    padding: 15px;
    margin: 0;
    font-weight: 500;
}

/* No recipes found message */
p {
    font-size: 18px;
    color: #999;
    margin-top: 40px;
}

/* Button for Navigation (Optional) */
button {
    background-color: #e63946;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #d62828;
}

/* Remove link styling for recipe items */
.recipe-link {
    text-decoration: none;
    color: inherit;
}

.recipe-link:hover .recipe-item {
    transform: translateY(-10px);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
}

</style>
</head>
<body>

    <div class="container">
        <h2>Recipes with <?= htmlspecialchars($ingredientName); ?></h2>

        <?php if (count($recipes) > 0): ?>
            <div class="recipes-list">
                <?php foreach ($recipes as $recipe): ?>
                    <!-- Wrap each recipe card in a clickable link -->
                    <a href="userviewRecipe.php?recipeID=<?= $recipe['recipeID']; ?>" class="recipe-link">
                        <div class="recipe-item">
                            <img src="<?= htmlspecialchars($recipe['recipeImage']); ?>" alt="<?= htmlspecialchars($recipe['recipeTitle']); ?>">
                            <h3><?= htmlspecialchars($recipe['recipeTitle']); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No recipes found with this ingredient.</p>
            <button onclick="window.history.back()">Go Back</button>
        <?php endif; ?>
    </div>

</body>
</html>
