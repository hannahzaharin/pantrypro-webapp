<?php
session_start();
require 'databaseconnection.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

// Fetch user's favorite recipes
$favoritesQuery = "SELECT recipes.recipeID, recipes.recipeTitle, recipes.recipeImage 
                   FROM favourite_recipe 
                   INNER JOIN recipes ON favourite_recipe.recipeID = recipes.recipeID 
                   WHERE favourite_recipe.userID = $userID";
$favoritesResult = mysqli_query($connect, $favoritesQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Favorite Recipes</title>
    <style>
        /* Minimalist Styling */
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .favorites-container {
            width: 80%;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .recipe-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .recipe-card img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #e0e0e0;
        }

        .recipe-title {
            padding: 15px;
            font-size: 1.1em;
            color: #388e3c;
        }
    </style>
</head>
<body>

<h1 style="text-align: center; color: #388e3c;">My Favorite Recipes</h1>

<div class="favorites-container">
    <?php if (mysqli_num_rows($favoritesResult) > 0): ?>
        <?php while ($favorite = mysqli_fetch_assoc($favoritesResult)): ?>
            <div class="recipe-card">
                <a href="userviewRecipe.php?recipeID=<?php echo $favorite['recipeID']; ?>">
                    <img src="<?php echo htmlspecialchars($favorite['recipeImage']); ?>" alt="Recipe Image">
                    <p class="recipe-title"><?php echo htmlspecialchars($favorite['recipeTitle']); ?></p>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center; color: #757575;">You have no favorite recipes yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
