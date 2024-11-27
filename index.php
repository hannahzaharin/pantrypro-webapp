<?php
// Include database connection
include("databaseconnection.php");

// Start session to check if user is logged in
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Get the selected letter from the URL
$selectedLetter = isset($_GET['letter']) ? strtoupper($_GET['letter']) : '';
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
        /* General page styles */
        body {
            background-color: #ffffff; /* Set background to white */
            color: #388e3c; /* Set font color to green */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
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

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    text-decoration: none;
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
        .recipe-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }
        .recipe-card {
            border: 1px solid #388e3c;
            border-radius: 10px;
            overflow: hidden;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .recipe-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .recipe-card h3 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .all-recipes-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .hero-content {
    position: relative;  /* Ensure the content stays on top */
    background-image: url('cookinggg.jpg'); /* Replace with the path to your image */
    background-size: cover; /* Make sure the image covers the entire area */
    background-position: center center; /* Center the image */
    color: white;  /* Ensure text is visible on the background */
    text-align: center;
    padding: 50px 20px; /* Add padding as necessary */
    height: 250px; /* Set a height or use min-height */
}

/* Optional: Adjusting text color or style */
.hero-content h1, .hero-content p {
    color: white; /* Ensure the text color contrasts with the background */
}

        .feature-item img,
        .review-section {
            color: #388e3c;
        }

        /* Toast Notification Styles */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px; /* To center the toast */
            background-color: #4CAF50; /* Green */
            color: white;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: opacity 0.3s ease-in-out;
        }

        .toast.show {
            visibility: visible;
            opacity: 1;
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

        .recipe-link {
    text-decoration: none;
    color: inherit; /* Keeps the text color unchanged */
}

.recipe-link:hover {
    text-decoration: none; /* Ensures underline doesn't appear on hover */
}

    </style>
</head>
<body>
    <!-- Hero Section -->
    <header class="hero-section">
        <nav>
    <ul class="nav-links">
        <li><a href="#">Home</a></li>
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


<?php
// Check if the search form was submitted
if (isset($_POST['searchQuery']) && !empty($_POST['searchQuery'])) {
    $searchQuery = mysqli_real_escape_string($connect, $_POST['searchQuery']);
    
    // Query to search for recipes matching the search query
    $query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage 
              FROM recipes 
              WHERE recipeTitle LIKE '%$searchQuery%' 
              OR recipeDescription LIKE '%$searchQuery%' 
              ORDER BY recipeID";
    
    $result = mysqli_query($connect, $query);
    
    // Display results below the search box
    echo '<section id="results">';
    echo '<h2>Search Results for: ' . htmlspecialchars($searchQuery) . '</h2>';
    
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="recipe-results">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="recipe-card">';
            echo '<img src="' . htmlspecialchars($row['recipeImage']) . '" alt="' . htmlspecialchars($row['recipeTitle']) . '" width="100">';
            echo '<h3><a href="userviewRecipe.php?recipeID=' . htmlspecialchars($row['recipeID']) . '">' . htmlspecialchars($row['recipeTitle']) . '</a></h3>';
            echo '<p>' . htmlspecialchars($row['recipeDescription']) . '</p>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No recipes found for: ' . htmlspecialchars($searchQuery) . '</p>';
    }

    echo '</section>';
    
    // Free the result and close the connection
    mysqli_free_result($result);
    mysqli_close($connect);
}
?>
        <?php if ($isLoggedIn): ?>
            <li><a href="logout.php" class="btn btn-primary">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="btn btn-primary">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="hero-content">
        <h1>Turn Your Pantry Into Delicious Meals!</h1>
        <p>Your kitchen companion for quick, easy, and delicious meals.</p>
        
         <!-- Always Visible Floating Back to Top Button -->
<button id="floatingBackToTop" title="Back to Top">⬆️</button>

        <!-- Browse Recipes by Name Section -->
        <section class="browse-by-name">
            <h2>Browse Recipes by Letter</h2>
            <div class="letter-container">
                <?php 
                // Generate letters A-Z as links
                foreach (range('A', 'Z') as $letter) {
                    echo '<a class="letter-link" href="browse_by_name.php?letter=' . $letter . '">' . $letter . '</a>';
                }
                ?>
            </div>
        </section>
    </div>
</header>

<style>
    .browse-by-name {
        text-align: center;
        margin: 10px 0;
    }

    .browse-by-name h2 {
        color: white; /* Ensures the 'Browse Recipes by Name' text is white */
    }

    .letter-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px; /* Adds space between letters */
    }

    .letter-link {
        text-decoration: none;
        font-size: 14px;
        padding: 8px 12px;
        border: 1px solid #ccc; /* Adds a border */
        border-radius: 5px; /* Rounds the corners */
        transition: background-color 0.3s, color 0.3s; /* Smooth transition for hover effect */
        color: white; /* Makes the letter links white */
    }

    .letter-link:hover {
        background-color: #4CAF50; /* Green background on hover */
        color: white; /* White text on hover */
    }
</style>



<!-- Browse by Ingredients Section -->
<section class="browse-by-ingredients">
    <h2>Browse Recipes by Ingredients</h2>
    <a href="browse_by_ingredients.php" class="all-recipes-btn">Browse Ingredients</a>
</section>


<style>
    .browse-by-ingredients {
        text-align: center;
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ccc; /* Adds a border around the section */
        border-radius: 5px; /* Rounds the corners */
        background-color: #f9f9f9; /* Light background color */
    }

    .ingredients-container {
        display: flex;
        flex-wrap: wrap; /* Allows items to wrap onto multiple lines */
        justify-content: center; /* Centers the items */
        gap: 20px; /* Space between items */
    }

    .ingredient-item {
        text-align: center;
        width: 100px; /* Fixed width for each ingredient item */
    }

    .ingredient-item img {
        width: 100px; /* Width of the image */
        height: 100px; /* Height of the image */
        border-radius: 50%; /* Circular frame */
        object-fit: cover; /* Ensures the image covers the frame */
        border: 2px solid #4CAF50; /* Green border around the image */
    }

    .ingredient-item span {
        display: block;
        margin-top: 8px; /* Space between image and text */
        font-size: 16px;
        color: #333; /* Darker text color */
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

<!-- Popular Recipes Section -->
<section class="recipe-section">
    <h2>Popular Recipes</h2>
    <div class="recipe-container">
        <?php
        $query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage FROM recipes ORDER BY recipeID LIMIT 3";
        $result = mysqli_query($connect, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<a href="userviewRecipe.php?recipeID=' . urlencode($row['recipeID']) . '" class="recipe-card recipe-link">';
                echo '<img src="' . htmlspecialchars($row['recipeImage']) . '" alt="' . htmlspecialchars($row['recipeTitle']) . '">';
                echo '<h3>' . htmlspecialchars($row['recipeTitle']) . '</h3>';
                echo '<p>' . htmlspecialchars(substr($row['recipeDescription'], 0, 80)) . '...</p>';
                echo '</a>';
            }
        } else {
            echo '<p>No recipes available at the moment.</p>';
        }
        mysqli_close($connect);
        ?>
    </div>
    <a href="allRecipes.php" class="all-recipes-btn">All Recipes</a>
</section>

<!-- Features Section -->
<section class="features-section">
    <h2>Why Use PantryPro?</h2>
    <div class="feature-container">
        
        <!-- Reason 1: Save Time and Reduce Food Waste -->
        <div class="feature-item">
            <img src="time-saving-icon.png" alt="Save Time and Reduce Waste">
            <p><strong>Save Time & Reduce Waste:</strong> PantryPro helps you use ingredients you already have, cutting down on waste and time spent deciding what to cook.</p>
        </div>

        <!-- Reason 2: Personalized Recipe Recommendations -->
        <div class="feature-item">
            <img src="personalized-recommendations-icon.png" alt="Personalized Recipe Recommendations">
            <p><strong>Personalized Recipes:</strong> Get tailored recipes based on your preferences and ingredients, making cooking a seamless and enjoyable experience.</p>
        </div>

        <!-- Reason 3: Easy-to-Follow Steps for All Levels -->
        <div class="feature-item">
            <img src="easy-steps-icon.png" alt="Easy-to-Follow Steps">
            <p><strong>Easy-to-Follow Steps:</strong> From beginner to expert, follow clear, step-by-step guides that make cooking fun and approachable for everyone.</p>
        </div>

    </div>
</section>



<!-- How It Works Section -->
<section class="how-it-works">
        <h2>How It Works</h2>
        <ol>
            <li>Input your ingredients.</li>
            <li>Get personalized recipes.</li>
            <li>Cook and enjoy!</li>
        </ol>
    </section>

    <!-- Community Section -->
    <section class="community-section">
        <h2>Join Our Cooking Community</h2>
        <p>Sign up for our newsletter to get weekly recipe inspiration!</p>
        <input type="email" placeholder="Your email...">
        <button>Subscribe</button>
    </section>
    


    <!-- Recipe Review Section -->
    <section class="review-section">
        <h2>Submit Your Review</h2>
        <form id="reviewForm">
            <input type="text" id="reviewInput" placeholder="Write your review..." required>
            <button type="submit">Submit Review</button>
        </form>
        <ul class="user-reviews"></ul>
    </section>

    <!-- Footer -->
    <footer>
        <div class="social-media">
            <a href="#">Instagram</a>
            <a href="#">Facebook</a>
        </div>
        <p>&copy; 2024 MyPantryChef. All rights reserved.</p>
    </footer>


    <!-- Toast Notification for Logout -->
<div id="logoutToast" class="toast">You have successfully logged out!</div>

<!-- Toast Notification for Login -->
<div id="loginToast" class="toast">
    Successfully logged in!
    <a href="dashboard.php"style="color: black;">Go to Dashboard</a>
</div>

<script>
    // Check if the logout query parameter is set in the URL
    <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
        // Show the logout toast notification
        var logoutToast = document.getElementById("logoutToast");
        logoutToast.classList.add("show");

        // Hide the logout toast after 3 seconds and redirect to home
        setTimeout(function() {
            logoutToast.classList.remove("show");
            window.location.href = "index.php"; // Redirect back to home
        }, 3000);
    <?php endif; ?>

    // Check if the login query parameter is set in the URL
    <?php if (isset($_GET['login']) && $_GET['login'] == 'success'): ?>
        // Show the login toast notification
        var loginToast = document.getElementById("loginToast");
        loginToast.classList.add("show");

        // Hide the login toast after 5 seconds
        setTimeout(function() {
            loginToast.classList.remove("show");
        }, 5000);
    <?php endif; ?>
</script>

    <script src="script.js"></script>
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

</body>
</html>