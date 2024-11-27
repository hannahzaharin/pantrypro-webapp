<?php
session_start();
include("databaseconnection.php");

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    
    // Fetch the user's name for personalized greeting
    $query = "SELECT userName FROM users WHERE userID = '$userID'";
    $result = mysqli_query($connect, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userName = $row['userName'];
    } else {
        echo "Error fetching user data.";
        exit;
    }

    // Fetch statistics for the user
    $totalRecipesSaved = getTotalRecipesSaved($userID);
    $totalIngredientsUsed = getTotalIngredientsUsed($userID);
    
} else {
    header("Location: login.php");
    exit;
}

// Query to fetch saved recipes
$savedRecipesQuery = "
    SELECT r.recipeID, r.recipeTitle, r.recipeImage 
    FROM saved_recipes sr
    JOIN recipes r ON sr.recipeID = r.recipeID
    WHERE sr.userID = '$userID'
";
$savedRecipesResult = mysqli_query($connect, $savedRecipesQuery);

// Function to get the total number of saved recipes
function getTotalRecipesSaved($userID) {
    global $connect;
    $query = "SELECT COUNT(*) AS total FROM saved_recipes WHERE userID = '$userID'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}



// Function to get the number of unique ingredients used
function getTotalIngredientsUsed($userID) {
    global $connect;
    $query = "SELECT COUNT(DISTINCT ingredientID) AS total FROM recipe_ingredients 
              JOIN saved_recipes sr ON recipe_ingredients.recipeID = sr.recipeID
              WHERE sr.userID = '$userID'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getTotalRecipesViewed($userID) {
    global $connect;
    $query = "SELECT COUNT(*) AS total FROM recipeviews WHERE userID = '$userID'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}


function getRecentRecipeInteractions($userID) {
    global $connect;
    // Get recent saved recipes or views (from both saved_recipes and recipe_views)
    $query = "
        (SELECT r.recipeID, r.recipeTitle, r.recipeImage, 'Saved' AS interactionType
        FROM saved_recipes sr
        JOIN recipes r ON sr.recipeID = r.recipeID
        WHERE sr.userID = '$userID' ORDER BY sr.dateSaved DESC)
        UNION
        (SELECT r.recipeID, r.recipeTitle, r.recipeImage, 'Viewed' AS interactionType
        FROM recipeviews rv
        JOIN recipes r ON rv.recipeID = r.recipeID
        WHERE rv.userID = '$userID' ORDER BY rv.viewDate DESC)
        LIMIT 5
    ";
    $result = mysqli_query($connect, $query);
    return $result;
}

function getPantryInventory($userID) {
    global $connect;
    $query = "SELECT ingredient_name, quantity, stocked_date, to_purchase FROM pantry_inventory WHERE userID = '$userID'";
    $result = mysqli_query($connect, $query);
    
    $inventory = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $inventory[] = $row;
    }

    return $inventory;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPantryChef - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
body {
            background-color: #ffffff; /* Set background to white */
            color: black; /* Set font color to green */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }


header {
    background-color: #A8D5BA; /* Light soft green color */
    padding: 10px;
}

.nav-links {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
}

.nav-links > li {
    position: relative;
    margin-right: 20px; /* Space between menu items */
}

.nav-links a {
    text-decoration: none;
    padding: 10px;
    color: black; /* Text color */
}

        /* Dashboard Specific Styles */
        .dashboard-container {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-header h1 {
            color: #2e7d32;
        }

        .stats-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #e0f5e0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            /*width: 400px; 
    height: 200px;  */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card h2 {
            color: #2e7d32;
            font-size: 1.5rem;
            margin: 10px 0;
        }
        /* Recent Interactions Section */
        .recent-interactions {
            margin-top: 40px;
            text-align: center;
        }

        .recent-recipe {
            display: inline-block;
            width: 200px;
            margin: 15px;
            text-align: center;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .recent-recipe img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .recent-recipe h3 {
            font-size: 1.1em;
            margin: 10px 0;
            color: #333;
        }

        .recent-recipe p {
            color: #777;
        }

        /* Saved Recipes Section */
        .saved-recipes {
            margin-top: 40px;
            text-align: center;
        }

        .saved-recipe {
            display: inline-block;
            width: 220px;
            margin: 20px;
            text-align: center;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .saved-recipe img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .saved-recipe h3 {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .saved-recipe .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .inventory-table th, .inventory-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .inventory-table th {
            background-color: #A8D5BA;
            color: white;
        }
        /* Footer */
        footer {
            background-color: #A8D5BA;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 50px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
                align-items: center;
            }

            .stat-card {
                width: 80%;
                margin-bottom: 20px;
            }

            .recent-recipe {
                width: 90%;
            }

            .saved-recipe {
                width: 90%;
            }
        }

        /* Modal background (overlay) */
        .modal {
            cursor: default;  /* Set to arrow cursor when hovering over the modal overlay */
        }

        /* Modal content (form container) */
        .modal-content {
            cursor: default;  /* Set to arrow cursor when hovering over the modal content */
        }

        /* Input fields inside the modal (where typing is allowed) */
        input, textarea {
            cursor: text; /* Text cursor when hovering over input fields */
        }

        /* Styles for the search container and suggestions dropdown */
        .search-container {
            position: relative;
            max-width: 800px;
            margin: 5px auto;
        }

        #recipe-search {
            width: 100%;
            padding: 8px;
            font-size: 14px;
        }

        .suggestions-container {
            position: absolute;
            background-color: #ffffff;
            border: 1px solid #ddd;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1;
            color: #333;
        }

        .suggestion-item {
            padding: 6px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        .no-results {
            padding: 6px;
            color: #666;
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
    <!-- Header -->
<header class="hero-section">
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="pantry_inventory.php">My Pantry</a></li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="grocery_list.php">Grocery List</a></li>
            <li><a href="meal_planner.php">Meal Planner</a></li>

                <div class="search-container">
    <input type="text" id="recipe-search" placeholder="Search for recipes..." onkeyup="showSuggestions(this.value)">
    <div id="suggestions" class="suggestions-container"></div>
</div>
                <!-- Logout Button -->
                <a href="logout.php" class="logout-button" style="margin-left: 5px; margin-right: 20px; background-color: #A8D5BA; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Logout</a>
            </div>
            </div>
        </ul>
    </nav>


        <h1>Dashboard</h1>
    </header>


    <!-- Always Visible Floating Back to Top Button -->
<button id="floatingBackToTop" title="Back to Top">⬆️</button>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome Back, <?php echo htmlspecialchars($userName); ?>!</h1>
            <p>Here's an overview of your cooking journey with PantryPro.</p>
        </div>

        <!-- Stats Section -->
        <div class="stats-container">
            <div class="stat-card">
                <h2><?php echo $totalRecipesSaved; ?></h2>
                <p>Total recipes saved in your profile!</p>
            </div>
            
            <div class="stat-card">
    <h2><?php echo getTotalRecipesViewed($userID); ?></h2>
    <p>Total recipes you've viewed!</p>
</div>

            <div class="stat-card">
                <h2><?php echo $totalIngredientsUsed; ?></h2>
                <p>Unique ingredients you've used in your recipes.</p>
            </div>
    </div>
            
            <div class="recent-interactions">
    <h2>Your Recent Recipe Interactions</h2>
    <?php
    $recentInteractions = getRecentRecipeInteractions($userID);
    if (mysqli_num_rows($recentInteractions) > 0) {
        while ($interaction = mysqli_fetch_assoc($recentInteractions)) {
    ?>
    <div class="recent-recipe">
        <img src="<?php echo htmlspecialchars($interaction['recipeImage']); ?>" alt="<?php echo htmlspecialchars($interaction['recipeTitle']); ?>" />
        <h3><?php echo htmlspecialchars($interaction['recipeTitle']); ?></h3>
        <p><?php echo $interaction['interactionType']; ?></p>
    </div>
    <?php
        }
    } else {
        echo "<p>You haven't interacted with any recipes recently.</p>";
    }
    ?>
</div>

        <!-- Saved Recipes Section -->
        <div class="saved-recipes">
            <h2>Your Saved Recipes</h2>
            <?php if (mysqli_num_rows($savedRecipesResult) > 0): ?>
                <?php while ($savedRecipe = mysqli_fetch_assoc($savedRecipesResult)): ?>
                    <div class="saved-recipe">
                        <img src="<?php echo htmlspecialchars($savedRecipe['recipeImage']); ?>" alt="<?php echo htmlspecialchars($savedRecipe['recipeTitle']); ?>" />
                        <h3><?php echo htmlspecialchars($savedRecipe['recipeTitle']); ?></h3>
                        <a href="userviewRecipe.php?recipeID=<?php echo $savedRecipe['recipeID']; ?>" class="button">View Recipe</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You have no saved recipes.</p>
            <?php endif; ?>
        </div>
    </div>


</div>
</section>

<script>

function showSuggestions(query) {
    if (query.length === 0) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "getSuggestions.php?query=" + encodeURIComponent(query), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("suggestions").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function showSuggestions(query) {
        if (query.length === 0) {
            document.getElementById("suggestions").innerHTML = "";
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "getSuggestions.php?query=" + encodeURIComponent(query), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("suggestions").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    function selectRecipe(recipeId) {
        window.location.href = "userviewRecipe.php?id=" + recipeId;
    }

    // Smooth scroll to the top when the button is clicked
document.getElementById("floatingBackToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 MyPantryChef. All rights reserved.</p>
    </footer>
</body>
</html>