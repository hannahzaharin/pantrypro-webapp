<?php
session_start();
include("databaseconnection.php");

// Fetch recipes based on search query only if a search term is provided
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
$recipes = [];

if ($searchQuery) {
    $query = "SELECT * FROM recipes WHERE recipeTitle LIKE '%$searchQuery%' OR recipeDescription LIKE '%$searchQuery%'";
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $recipes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Search and Grocery List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }

        .search-container, .selected-recipes {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 5px;
        }

        .suggestion-list {
            margin-top: 10px;
            border-top: 1px solid #ddd;
            max-height: 200px;
            overflow-y: auto;
        }

        .suggestion-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        .suggestion-item img {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .selected-recipes .recipe-item {
            display: flex;
            justify-content: start;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .selected-recipes .recipe-item:last-child {
            border-bottom: none;
        }

        .selected-recipes img {
            width: 80px;
            height: auto;
            margin-right: 10px;
        }

        button {
            padding: 8px 12px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

         /* Floating Action Button for Back to Top */
#floatingBackToTop {
    position: fixed;                /* Keeps the button fixed in place */
    bottom: 30px;                   /* Distance from the bottom */
    right: 30px;                    /* Distance from the right */
    width: 50px;                    /* Width for a circular button */
    height: 50px;                   /* Height for a circular button */
    border-radius: 50%;             /* Makes the button circular */
    background-color: #4CAF50;      /* Customize color */
    color: white;                   /* White color for the icon/text */
    font-size: 24px;                /* Size of the arrow */
    display: flex;                  /* Centers the icon within */
    align-items: center;            /* Centers the icon vertically */
    justify-content: center;        /* Centers the icon horizontally */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Adds a shadow for floating effect */
    cursor: pointer;                /* Pointer cursor for interactivity */
    z-index: 1000;                  /* Ensures it's above other elements */
}

/* Hover effect */
#floatingBackToTop:hover {
    background-color: #45a049;      /* Slightly darker green on hover */
    transform: scale(1.1);          /* Small scale effect on hover */
}
    </style>
</head>
<body>

<div class="container">

    <!-- Search Container -->
    <div class="search-container">
        <h2>Search Recipes</h2>
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Enter recipe name or ingredient" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Suggestions List - Only appears if search query is present -->
        <?php if ($searchQuery && !empty($recipes)): ?>
            <div class="suggestion-list">
                <?php foreach ($recipes as $recipe): ?>
                    <div class="suggestion-item" onclick="selectRecipe(<?php echo htmlspecialchars(json_encode($recipe)); ?>)">
                        <img src="<?php echo htmlspecialchars($recipe['recipeImage']); ?>" alt="Recipe Image">
                        <div>
                            <strong><?php echo htmlspecialchars($recipe['recipeTitle']); ?></strong>
                            <p><?php echo htmlspecialchars($recipe['recipeDescription']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($searchQuery): ?>
            <p>No recipes found for "<?php echo htmlspecialchars($searchQuery); ?>".</p>
        <?php endif; ?>
    </div>

    <!-- Always Visible Floating Back to Top Button -->
 <button id="floatingBackToTop" title="Back to Top">⬆️</button>

    <!-- Selected Recipes Display -->
    <div class="selected-recipes">
        <h2>Selected Recipes</h2>
        <div id="selectedRecipesContainer"></div>
    </div>

</div>

<script>
    // JavaScript function to handle selected recipes
    function selectRecipe(recipe) {
        // Get container to display selected recipes
        const container = document.getElementById('selectedRecipesContainer');
        
        // Check if recipe is already added
        if (container.querySelector(`[data-id='${recipe.recipeID}']`)) {
            alert('This recipe is already selected.');
            return;
        }

        // Create elements to display recipe details
        const recipeDiv = document.createElement('div');
        recipeDiv.classList.add('recipe-item');
        recipeDiv.setAttribute('data-id', recipe.recipeID); // To prevent duplicate selection

        const img = document.createElement('img');
        img.src = recipe.recipeImage;
        img.alt = 'Recipe Image';

        const title = document.createElement('strong');
        title.textContent = recipe.recipeTitle;

        const description = document.createElement('p');
        description.textContent = recipe.recipeDescription;

        // Append elements to the selected recipe div
        recipeDiv.appendChild(img);
        recipeDiv.appendChild(title);
        recipeDiv.appendChild(description);

        // Add the selected recipe div to the container
        container.appendChild(recipeDiv);
    }

     // Smooth scroll to the top when the button is clicked
document.getElementById("floatingBackToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

</body>
</html>
