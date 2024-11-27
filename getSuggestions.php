<?php
include("databaseconnection.php");

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($connect, $_GET['query']);
    
    // Search for recipes that match the query
    $sql = "SELECT recipeID, recipeTitle FROM recipes 
            WHERE recipeTitle LIKE '%$query%' 
            ORDER BY recipeTitle LIMIT 5";
    $result = mysqli_query($connect, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<a href="userviewRecipe.php?recipeID=' . htmlspecialchars($row['recipeID']) . '" class="suggestion-item">' . htmlspecialchars($row['recipeTitle']) . '</a><br>';
        }
    } else {
        echo '<p class="no-results">No recipes found</p>';
    }

    mysqli_free_result($result);
}

mysqli_close($connect);
?>

<style>
    .suggestions-container {
    position: absolute;
    background-color: #ffffff;
    border: 1px solid #ddd;
    max-width: 100%;
    width: 100%;
    z-index: 1;
    color: #333; /* Make sure the text color is dark */
}

.suggestion-item {
    display: block;
    padding: 8px;
    text-decoration: none;
    color: #333; /* Ensure text color is visible */
    cursor: pointer;
}

.suggestion-item:hover {
    background-color: #f0f0f0;
}

.no-results {
    padding: 8px;
    color: #666;
}

</style>