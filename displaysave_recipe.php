<?php
session_start();
include('databaseconnection.php');

if (!isset($_SESSION['userID'])) {
    echo "Please log in to view your saved recipes.";
    exit;
}

$userID = $_SESSION['userID'];

// Query to fetch saved recipes
$savedRecipesQuery = "
    SELECT r.recipeID, r.recipeTitle, r.recipeImage 
    FROM saved_recipes sr
    JOIN recipes r ON sr.recipeID = r.recipeID
    WHERE sr.userID = '$userID'
";
$savedRecipesResult = mysqli_query($connect, $savedRecipesQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 100%;
            max-width: 800px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            border-radius: 8px;
        }

        .saved-recipe {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            margin: 15px 0;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .saved-recipe img {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 8px;
        }

        .saved-recipe h3 {
            margin: 10px 0;
            font-size: 1.2em;
            color: #333;
        }

        .saved-recipe .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .saved-recipe .button:hover {
            background-color: #45a049;
        }

        .no-recipes {
            text-align: center;
            font-size: 1.2em;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if (mysqli_num_rows($savedRecipesResult) > 0) {
        while ($savedRecipe = mysqli_fetch_assoc($savedRecipesResult)) {
            ?>
            <div class="saved-recipe">
                <img src="<?php echo htmlspecialchars($savedRecipe['recipeImage']); ?>" alt="<?php echo htmlspecialchars($savedRecipe['recipeTitle']); ?>" />
                <h3><?php echo htmlspecialchars($savedRecipe['recipeTitle']); ?></h3>
                <a href="viewRecipe.php?recipeID=<?php echo $savedRecipe['recipeID']; ?>" class="button">View Recipe</a>
            </div>
            <?php
        }
    } else {
        echo "<div class='no-recipes'>You have no saved recipes.</div>";
    }
    ?>
</div>

</body>
</html>
