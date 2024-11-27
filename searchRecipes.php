

<?php
// Include database connection
include("databaseconnection.php");

// Check if the 'ingredient' parameter is provided
if (isset($_GET['ingredient'])) {
    // Get the ingredient from the URL parameters
    $ingredient = $_GET['ingredient'];
    
    // Prepare SQL query to search for recipes that contain the specified ingredient
    $query = "SELECT recipeID, recipeTitle FROM recipes WHERE ingredients LIKE ?";
    $stmt = $connect->prepare($query);

    // Use wildcard characters to match any recipe containing the ingredient
    $ingredient = "%$ingredient%";
    $stmt->bind_param("s", $ingredient);
    $stmt->execute();

    // Get the results of the query
    $result = $stmt->get_result();

    // Initialize an array to store recipes
    $recipes = [];
    while ($row = $result->fetch_assoc()) {
        // Add each recipe found to the array
        $recipes[] = $row;
    }

    // Output the recipes array as JSON
    header('Content-Type: application/json');
    echo json_encode($recipes);
} else {
    // If 'ingredient' is not set, return an empty JSON array
    echo json_encode([]);
}

// Close the database connection
$connect->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPantryChef - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS Styles */
        body {
            background-color: #ffffff;
            color: #388e3c;
            font-family: 'Poppins', sans-serif;
        }

        .search-container {
            position: relative;
            width: 300px;
            margin: 0 auto;
        }

        .search-container input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-results {
            position: absolute;
            width: 100%;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }

        .search-results div {
            padding: 8px;
            cursor: pointer;
        }

        .search-results div:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <!-- Search Section -->
    <div class="search-container">
        <input type="text" placeholder="Search for recipes..." id="searchInput">
        <div id="searchResults" class="search-results"></div>
    </div>

    <script>
        document.getElementById("searchInput").addEventListener("input", function () {
            let query = this.value;
            if (query.length > 1) { // Start searching after typing 2+ characters
                fetch(`searchRecipes.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        let searchResults = document.getElementById("searchResults");
                        searchResults.innerHTML = "";
                        searchResults.style.display = "block";
                        
                        if (data.length > 0) {
                            data.forEach(recipe => {
                                let resultDiv = document.createElement("div");
                                resultDiv.textContent = recipe.recipeTitle;
                                resultDiv.onclick = function() {
                                    window.location.href = `userviewRecipe.php?recipeID=${recipe.recipeID}`;
                                };
                                searchResults.appendChild(resultDiv);
                            });
                        } else {
                            let noResults = document.createElement("div");
                            noResults.textContent = "No recipes found.";
                            searchResults.appendChild(noResults);
                        }
                    });
            } else {
                document.getElementById("searchResults").style.display = "none";
            }
        });
    </script>
</body>
</html>

