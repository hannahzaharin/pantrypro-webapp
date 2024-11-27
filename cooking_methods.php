<?php
// Include the database connection file
include("databaseconnection.php");

// Start the session for login check
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Initialize a variable to hold selected cooking method and results
$selectedCategory = '';

// Check if 'method' is passed in the URL
if (isset($_GET['category'])) {
    $selectedCategory = $_GET['category'];

    // Initialize recipes array
    $recipes = [];

    // Fetch recipes based on the selected cooking method
    $query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage FROM recipes WHERE recipeCategory = ?";
    if ($stmt = $connect->prepare($query)) {
        $stmt->bind_param("s", $selectedCategory);
        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        // Handle query preparation error
        echo "Error in preparing statement: " . htmlspecialchars($connect->error);
    }
} else {
    $selectedCategory = "none"; // Default message if no method is selected
    $recipes = [];
}

// Close the database connection
$connect->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PantryPro - Category Meal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>/* General Styling */
 body {
            background-color: #ffffff; /* Set background to white */
            color: #388e3c; /* Set font color to green */
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
    color: #333; /* Text color */
}

h1, h2, h3, p, a {
            color: #388e3c; /* Set primary font color to green for all headings, paragraphs, and links */
        }
        .btn, .all-recipes-btn {
            background-color:  #A8D5BA; /* Button background color */
            color: white; /* Button text color */
        }
        .hero-section {
            text-align: center;
            padding: 20px;
        }

.dropdown-content {
    display: none; /* Hide by default */
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2); /* Dropdown shadow */
    z-index: 1;
    min-width: 160px;
    top: 100%; /* Position it below the link */
}

.dropdown-content a {
    display: block;
    padding: 10px;
    color: #333;
}

.dropdown-content a:hover {
    background-color: #f1f1f1; /* Highlight on hover */
}

.dropdown:hover .dropdown-content {
    display: block; /* Show dropdown on hover */
}


h1, h2 {
    text-align: center;
    color: #5bb85c;
}

section {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
}

form label {
    font-size: 1.2em;
    margin-bottom: 10px;
}

form select {
    padding: 10px;
    width: 50%;
    font-size: 1em;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: border-color 0.3s;
}

form select:focus {
    outline: none;
    border-color: #5bb85c;
}

form button {
    padding: 10px 20px;
    font-size: 1em;
    color: white;
    background-color: #5bb85c;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

form button:hover {
    background-color: #4cae4c;
}

/* Recipe Cards */
.recipe-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.recipe-card {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    transition: transform 0.3s ease;
    text-align: center;
}

.recipe-card:hover {
    transform: scale(1.05);
}

.recipe-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.recipe-card h3 {
    color: #5bb85c;
    font-size: 1.2em;
    margin: 10px 0;
}

.recipe-card p {
    padding: 0 15px;
    color: #555;
}

/* Footer Styling */
footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: white;
}

footer a {
    color: #5bb85c;
    text-decoration: none;
    margin: 0 10px;
}

footer a:hover {
    text-decoration: underline;
}

/* Style for the dropdown */
#cookingMethod {
    width: 250px; /* Adjust width as needed */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    background-color: #fff;
    background-image: url('images/dropdown-arrow.png'); /* Optional: dropdown arrow image */
    background-position: right 10px center;
    background-repeat: no-repeat;
}

/* Style for the option icons */
.option-icon {
    display: inline-block;
    width: 20px; /* Icon width */
    height: 20px; /* Icon height */
    vertical-align: middle;
    margin-right: 5px; /* Space between icon and text */
}

/* Additional style for info text */
#methodInfo {
    margin-top: 10px;
    font-size: 14px;
    color: #333;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9; /* Light background for visibility */
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
    <!-- Navigation Menu -->
    <!-- Hero Section -->
    <header class="hero-section">
        <nav>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <a href="#">Meal</a>
            <div class="dropdown-content">
                <a href="cooking_methods.php?category=Breakfast" style="color: brown;">Breakfast</a>
                <a href="cooking_methods.php?category=Lunch" style="color: orange;">Lunch</a>
                <a href="cooking_methods.php?category=Dinner" style="color: green;">Dinner</a>
            </div>
        </li>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
        <li class="dropdown">
                <a href="#">Techniques</a>
                <div class="dropdown-content">
                    <a href="techniques.php?technique=blanching">Blanching</a>
                    <a href="techniques.php?technique=sautéing">Sautéing</a>
                    <a href="techniques.php?technique=simmering">Simmering</a>
                </div>
            </li>
            <div class="search-container">
    <input type="text" id="recipe-search" placeholder="Search for recipes..." onkeyup="showSuggestions(this.value)">
    <div id="suggestions" class="suggestions-container"></div>
</div>
        <?php if ($isLoggedIn): ?>
            <li><a href="logout.php" class="btn btn-primary">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="btn btn-primary">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
    </header>

    <!-- Always Visible Floating Back to Top Button -->
 <button id="floatingBackToTop" title="Back to Top">⬆️</button>

<!-- Cooking Method Selection Form -->
<section>
    <h1>Browse Recipes by Category</h1>
    <form action="cooking_methods.php" category="GET">
        <label for="recipeCategory">Select a Category:</label>
        <img id="categoryIcon" src="" alt="Category Meal Icon" style="display: none;">
        <div id="categoryInfo" style="margin-top: 10px; font-size: 14px; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;"></div>
        
        <select name="category" id="recipeCategory" onchange="updateRecipeCategoryInfo()" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Brekafast" data-icon="baking.jpg" <?php echo $selectedCategory == 'Breakfast' ? 'selected' : ''; ?>>Breakfast</option>
            <option value="Lunch" data-icon="images/grilling-icon.jpg" <?php echo $selectedCategory == 'Lunch' ? 'selected' : ''; ?>>Lunch</option>
            <option value="Dinner" data-icon="images/roasting-icon.png" <?php echo $selectedCategory == 'Dinner' ? 'selected' : ''; ?>>Dinner</option>
        </select>
        <button type="submit">Find Recipes</button>
    </form>
</section>

<!-- Display Recipes Based on Cooking Method -->
<section>
    <h2>Recipes for: <?php echo htmlspecialchars($selectedCategory); ?></h2>
    <div class="recipe-container">
        <?php if (!empty($recipes)): ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <img src="<?php echo htmlspecialchars($recipe['recipeImage']); ?>" alt="<?php echo htmlspecialchars($recipe['recipeTitle']); ?>">
                    <h3><?php echo htmlspecialchars($recipe['recipeTitle']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($recipe['recipeDescription'], 0, 80)) . '...'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recipes found for the selected category meal.</p>
        <?php endif; ?>
    </div>
</section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Your Website</p>
    </footer>

    <script>
    function updateRecipeCategoryInfo() {
        const select = document.getElementById('recipeCategory');
        const selectedOption = select.options[select.selectedIndex];
        const categoryInfo = document.getElementById('categoryInfo');
        const categoryIcon = document.getElementById('categoryIcon');

        // Reset icon and info
        categoryIcon.src = '';
        categoryInfo.textContent = '';

        // Display information based on the selected method
        const categoryName = selectedOption.value;
        switch (categoryName) {
            case 'Breakfast':
                categoryInfo.textContent = 'The most important meal of the day';
                categoryIcon.src = selectedOption.getAttribute('data-icon');
                break;
            case 'Lunch':
                categoryInfo.textContent = 'A light meal eaten in the middle of the day';
                categoryIcon.src = selectedOption.getAttribute('data-icon');
                break;
            case 'Dinner':
                categoryInfo.textContent = 'The main meal of the day eaten in the evening';
                categoryIcon.src = selectedOption.getAttribute('data-icon');
                break;
            default:
                categoryInfo.textContent = '';
                break;
        }
        categoryIcon.style.display = categoryIcon.src ? 'inline' : 'none';
    }

    // Call the function on page load to set the initial state
    document.addEventListener('DOMContentLoaded', updateRecipeCategoryInfo);

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
</body>
</html>
