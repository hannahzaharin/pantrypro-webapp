<?php
// Include database connection
include("databaseconnection.php");

// Start session to check if user is logged in
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

// Get the selected letter from the URL
$selectedLetter = isset($_GET['letter']) ? strtoupper($_GET['letter']) : '';

// Pagination setup
$recipesPerPage = 5;  // Number of recipes to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get current page number
$offset = ($page - 1) * $recipesPerPage;  // Calculate offset for query

// Query to get the total number of recipes starting with the selected letter
$totalQuery = "SELECT COUNT(*) as total FROM recipes WHERE recipeTitle LIKE '" . $selectedLetter . "%'";
$totalResult = mysqli_query($connect, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecipes = $totalRow['total'];  // Total number of recipes

// Query to get recipes that start with the selected letter
$query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage FROM recipes WHERE recipeTitle LIKE '" . $selectedLetter . "%' ORDER BY recipeTitle LIMIT $offset, $recipesPerPage";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyPantryChef - Browse by Letter: <?php echo $selectedLetter; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic reset and styling */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            color: #333;
        }

        header {
            background-color: #A8D5BA;
            padding: 2rem 0;
            text-align: center;
            color: #fff;
        }

        header h1 {
            font-size: 1.5rem;
            margin: 10;
        }

        .recipe-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 2rem;
        }

        .recipe-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 250px;
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit text color */
        }

        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
        }

        .recipe-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .recipe-card a {
    text-decoration: none; /* Remove underline */
    color: inherit; /* Inherit color from parent */
}


        .recipe-card h3 {
            margin: 1rem;
            font-size: 1.25rem;
            color: #4CAF50;
        }

        .recipe-card p {
            margin: 0 1rem 1.5rem 1rem;
            color: #666;
            font-size: 0.9rem;
        }

        /* Button styles for 'No recipes available' */
        .no-recipes-message {
            text-align: center;
            font-size: 1.2rem;
            color: #888;
            padding: 2rem;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: #A8D5BA;
            color: #fff;
            font-size: 0.9rem;
        }

        footer p {
            margin: 0;
        }

        .pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.page-link {
    padding: 8px 16px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.page-link:hover {
    background-color: #45a049;
}

.page-link.active {
    background-color: #2E8B57;
    font-weight: bold;
}

.back-to-home-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alphabet-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}

.alphabet-nav .letter-link {
    font-size: 18px;
    text-decoration: none;
    color: #4CAF50;
    padding: 5px;
    transition: background-color 0.3s;
}

.alphabet-nav .letter-link:hover {
    background-color: #4CAF50;
    color: white;
}

/* Browse by Letter Section */
.browse-by-name {
    text-align: center;
    margin: 10px 0;
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
}

.letter-link:hover {
    background-color: #4CAF50; /* Green background on hover */
    color: white; /* White text on hover */
}

/* Back to Homepage Button */
.back-to-home-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    font-size: 18px;
    margin-bottom: 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.back-to-home-btn:hover {
    background-color: #45a049;
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
        .hero-content h1 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }
        .hero-content p {
            font-size: 1.2em;
            margin-bottom: 1.5em;
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

.back-to-home-btn.floating {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #A8D5BA;
    color: white;
    padding: 12px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 18px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s;
}

.back-to-home-btn.floating:hover {
    background-color: #A8D5BA;
}
    </style>
</head>
<body>
        <header>
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
        </nav>
        <h1>Recipes Starting with "<?php echo $selectedLetter; ?>"</h1>
</header>

<!-- Browse by Another Letter Section -->
<section class="browse-by-name">
    <div class="letter-container">
        <?php 
        foreach (range('A', 'Z') as $letter) {
            echo '<a href="browse_by_name.php?letter=' . $letter . '#recipe-results" class="letter-link">' . $letter . '</a>';
        }
        ?>
    </div>
</section>

<section class="recipe-container" id="recipe-results">
        <?php
        if ($selectedLetter) {
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="recipe-card">';
                    echo '<a href="userviewRecipe.php?recipeID=' . $row['recipeID'] . '">';
                    echo '<img src="' . htmlspecialchars($row['recipeImage']) . '" alt="' . htmlspecialchars($row['recipeTitle']) . '">';
                    echo '<h3>' . htmlspecialchars($row['recipeTitle']) . '</h3>';
                    echo '<p>' . htmlspecialchars(substr($row['recipeDescription'], 0, 80)) . '...</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>No recipes available starting with "' . $selectedLetter . '".</p>';
            }
        } else {
            echo '<p>Please select a letter to browse recipes.</p>';
        }
        ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            $totalPages = ceil($totalRecipes / $recipesPerPage);  // Calculate total number of pages
            if ($totalPages > 1) {
                // Display previous page link if not on the first page
                if ($page > 1) {
                    echo '<a href="browse_by_name.php?letter=' . $selectedLetter . '&page=' . ($page - 1) . '" class="page-link">&laquo; Previous</a>';
                }

                // Display page number links
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<a href="browse_by_name.php?letter=' . $selectedLetter . '&page=' . $i . '" class="page-link' . ($i == $page ? ' active' : '') . '">' . $i . '</a>';
                }

                // Display next page link if not on the last page
                if ($page < $totalPages) {
                    echo '<a href="browse_by_name.php?letter=' . $selectedLetter . '&page=' . ($page + 1) . '" class="page-link">Next &raquo;</a>';
                }
            }
            ?>
        </div>
        <a href="index.php" class="back-to-home-btn floating">Back to Homepage</a>

    </section>
    <footer>
        <p>&copy; 2024 MyPantryChef. All rights reserved.</p>
    </footer>

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
    </script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connect);
?>
