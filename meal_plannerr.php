<?php
// Include database connection
include("databaseconnection.php");
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
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
            color: #2e7d32;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 20px;
            color: #2e7d32;
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

        body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

header h1 {
    text-align: center;
    color: #4CAF50;
    margin-bottom: 10px;
}

header p {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}

.form-container .btn-container {
    display: flex;
    justify-content: center;
    gap: 10px; /* Adjust the gap between the buttons */
}

.form-container .btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    flex-grow: 1; /* This makes the buttons expand to fill the available space if needed */
}

.form-container .btn:hover {
    background-color: #45a049;
}

.form-container {
    text-align: center;
    margin-bottom: 20px;
}

.form-container label {
    font-weight: 600;
    color: #333;
}

.form-container input[type="date"] {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-right: 10px;
}

.form-container .btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-container .btn:hover {
    background-color: #45a049;
}

/* Meal plan container */
.meal-plan-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-around;
    margin-top: 20px;
}

/* Individual meal card styling */
.meal-card {
    width: 30%;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    text-align: center;
    transition: transform 0.3s;
}

.meal-card:hover {
    transform: scale(1.05);
}

.meal-card h3 {
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    margin: 0;
}

.meal-card img {
    width: 100%;
    height: auto;
    max-height: 200px;
    object-fit: cover;
    display: block;
}

.meal-card p {
    padding: 15px;
    color: #555;
}

.meal-card a {
    display: inline-block;
    margin: 10px 0;
    color: #4CAF50;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s;
}

.meal-card a:hover {
    color: #45a049;
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


        <h1>Welcome to Your Meal Planner</h1>
    </header>


    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <p>Here's an overview of your cooking journey with PantryPro.</p>
        </div>

        <div class="form-container">
    <!-- Meal Plan Form -->
    <form method="POST" action="generateMealPlan.php">
        <label for="mealDate">Select Date for Meal Plan:</label>
        <!-- Display the meal date if it's set -->
        <input type="date" id="mealDate" name="mealDate" value="<?php echo isset($_GET['mealDate']) ? $_GET['mealDate'] : ''; ?>" required>
        
        <!-- Generate Meal Plan Button -->
        <button type="submit" name="generateMealPlan" class="btn">Generate Meal Plan</button>
    </form>

    <?php
    // Check if a meal plan was generated and display regenerate button with the date
    if (isset($_GET['generated']) && $_GET['generated'] == 'true' && isset($_GET['mealDate'])) {
        $mealDate = $_GET['mealDate']; // Get the meal date from the URL

        // Display regenerate button with the meal date retained in a hidden field
        echo "<form method='POST' action='generateMealPlan.php'>";
        echo "<input type='hidden' name='mealDate' value='$mealDate'>"; // Keep the mealDate
        echo "<button type='submit' name='regenerateMealPlan' class='btn'>Regenerate Meal Plan</button>";
        echo "</form>";
    }
    ?>
</div>


    <?php
    // Check if a meal plan was generated
    if (isset($_GET['generated']) && $_GET['generated'] == 'true' && isset($_GET['mealDate'])) {
        $mealDate = $_GET['mealDate'];
        $userID = $_SESSION['userID'];

        // Fetch the meal plan from the database
        $mealCategories = ['Breakfast', 'Lunch', 'Dinner'];
        echo "<div class='meal-plan-container'>";

        foreach ($mealCategories as $category) {
            $mealPlanQuery = "SELECT recipes.recipeTitle, recipes.recipeDescription, recipes.recipeImage, recipes.recipeID
                              FROM temp_meal_plans 
                              JOIN recipes ON temp_meal_plans.recipeID = recipes.recipeID
                              WHERE temp_meal_plans.userID = '$userID' 
                              AND temp_meal_plans.mealDate = '$mealDate'
                              AND temp_meal_plans.mealCategory = '$category'";

            $mealPlanResult = mysqli_query($connect, $mealPlanQuery);
            if ($mealPlanResult && mysqli_num_rows($mealPlanResult) > 0) {
                $mealPlan = mysqli_fetch_assoc($mealPlanResult);
                echo "<div class='meal-card'>";
                echo "<h3>$category</h3>";
                echo "<img src='" . $mealPlan['recipeImage'] . "' alt='" . $mealPlan['recipeTitle'] . "'>";
                echo "<p>" . $mealPlan['recipeDescription'] . "</p>";
                echo "<a href='userviewRecipe.php?recipeID=" . $mealPlan['recipeID'] . "'>View Recipe</a>";
                echo "</div>";
            } else {
                echo "<div class='meal-card'>";
                echo "<h3>$category</h3>";
                echo "<p>No recipe available for this category.</p>";
                echo "</div>";
            }
        }

        echo "</div>";
        echo "<form method='POST' action='saveMealPlan.php'>";
        echo "<button type='submit' name='saveMealPlan' class='btn'>Save Meal Plan</button>";
        echo "<button type='submit' name='regenerateMealPlan' class='btn' formaction='generateMealPlan.php'>Regenerate Meal Plan</button>";
        echo "<input type='hidden' name='mealDate' value='$mealDate'>";
        echo "</form>";
    }
    ?>
</div>

</body>
</html>

