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
    <title>Meal Planning</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>

        /* General styling */

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
    margin-top: 0;
    margin-bottom: 20px; /* or adjust as needed */
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

.hero-section {
    margin: 0;
    padding: 0;
}

.hero-section header {
    background-color: #A8D5BA; /* Specific styling for hero header */
}

.form-container header {
    /* Specific styling for form container header, if needed */
}


       /* General styling */
       body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    margin: auto;
    padding: 0; /* Reduce padding if needed */
    width: 100%;
}
/* Navigation Styling */
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

.nav-links .search-container {
    margin-left: auto;
    display: flex;
    align-items: center;
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
/* Footer */
footer {
            background-color: #A8D5BA;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 50px;
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
    <!-- Header -->
    <header class="hero-section">
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="pantry_inventory.php">My Pantry</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="grocery_list.php">Grocery List</a></li>
                <li><a href="#">Meal Planner</a></li>

                <div class="search-container">
                    <input type="text" id="recipe-search" placeholder="Search for recipes..." onkeyup="showSuggestions(this.value)">
                    <div id="suggestions" class="suggestions-container"></div>
                </div>
                <!-- Logout Button -->
                <a href="logout.php" class="logout-button" style="margin-left: 5px; margin-right: 20px; background-color: #A8D5BA; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Logout</a>
            </ul>
        </nav>
    </header>

    <header>
        <h1>Welcome to Your Meal Planner</h1>
        <p>Plan your meals for the day and stay healthy!</p>
    </header>

<!-- Always Visible Floating Back to Top Button -->
<button id="floatingBackToTop" title="Back to Top">⬆️</button>



    <div class="form-container">
    <!-- Meal Plan Form -->
    <form method="POST" action="generateMealPlan.php">
        <label for="mealDate">Select Date for Meal Plan:</label>
        <input type="date" id="mealDate" name="mealDate" value="<?php echo isset($_GET['mealDate']) ? $_GET['mealDate'] : ''; ?>" required>
        <button type="submit" name="generateMealPlan" class="btn">Generate Meal Plan</button>
    </form>

    <?php
    // Check if a meal plan was generated and display regenerate button with the date
    if (isset($_GET['generated']) && $_GET['generated'] == 'true' && isset($_GET['mealDate'])) {
        $mealDate = $_GET['mealDate']; // Get the meal date from the URL
        echo "<form method='POST' action='generateMealPlan.php'>";
        echo "<input type='hidden' name='mealDate' value='$mealDate'>";
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






