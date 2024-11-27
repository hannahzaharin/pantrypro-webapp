<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
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

    .recipe-link {
        text-decoration: none;
        color: inherit;
    }

    .recipe-card h3, .recipe-card p {
        margin: 0;
        padding: 5px 0;
    }

    /* Styling for image consistency */
    .recipe-card img {
        width: 100%; /* Set image width to 100% of the card */
        height: 200px; /* Fixed height for all images */
        object-fit: cover; /* Ensure images cover the area without distortion */
        border-radius: 8px; /* Optional: rounded corners */
        margin-bottom: 10px; /* Space between the image and text */
    }

    /* Optional: Add padding and a box shadow to make the recipe cards stand out */
    .recipe-card {
        border: 1px solid #ddd; /* Light border */
        border-radius: 8px;
        padding: 15px;
        background-color: #f9f9f9;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease; /* Smooth hover effect */
    }

    .recipe-card:hover {
        transform: translateY(-5px); /* Slight lift effect on hover */
    }

    /* Container for all recipe items */
/* Main container for all recipe items */
/* Main container for all recipe items */
.recipes-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 columns */
    gap: 10px; /* Smaller gap between items */
    padding: 10px;
}

/* Each recipe tile */
.recipe-container {
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
    height: 300px; /* Fixed height for uniformity */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.recipe-container:hover {
    transform: scale(1.03);
}

/* Styling for the image */
.recipe-container img {
    width: 100%;
    height: 160px; /* Fixed height for consistent image size */
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

/* Info section with title and description */
.recipe-info {
    padding: 10px;
    text-align: center;
    color: #fff; /* White text for contrast */
}

/* Recipe title styling */
.recipe-info h3 {
    font-size: 1em;
    margin: 8px 0 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #388e3c; /* White text */
}

/* Description styling */
.recipe-info p {
    color:#388e3c; /* Light color for text for better readability */
    font-size: 0.85em;
    line-height: 1.2em;
    max-height: 2.4em; /* Limits to 2 lines */
    overflow: hidden;
    margin: 0;
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

<!-- Hero Section -->
<header class="hero-section">
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li class="dropdown">
                <a href="#">Search Recipes</a>
                <div class="dropdown-content">
                    <a href="cooking_methods.php?method=Baking" style="color: brown;">Baking</a>
                    <a href="cooking_methods.php?method=Grilling" style="color: orange;">Grilling</a>
                    <a href="cooking_methods.php?method=Roasting" style="color: green;">Roasting</a>
                    <a href="cooking_methods.php?method=Frying" style="color: red;">Frying</a>
                    <a href="cooking_methods.php?method=Pressure-cooking" style="color: purple;">Pressure Cooking</a>
                    <a href="cooking_methods.php?method=Microwaving" style="color: darkblue;">Microwaving</a>
                </div>
            </li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
               <!-- Techniques and Search Container -->
            <div style="display: flex; align-items: center;"></div>
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
                </li>
                    <li><a href="login.php" class="btn btn-primary">Login</a></li>
                <?php ?>
            </ul>
        </nav>

 <!-- Always Visible Floating Back to Top Button -->
 <button id="floatingBackToTop" title="Back to Top">⬆️</button>
            
        <h2>All Recipes</h2>
<div class="recipes-container">
    <?php
    include("databaseconnection.php");

    if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
        <script>
            window.onload = function() {
                alert("Recipe has been deleted.");
            };
        </script>
    <?php endif;

    $query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage FROM recipes ORDER BY recipeID";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="recipe-container">';
            echo '<a href="userviewRecipe.php?recipeID=' . $row['recipeID'] . '" class="recipe-link">';
            echo '<div class="recipe-tile">';
            echo '<img src="' . $row['recipeImage'] . '" alt="' . $row['recipeTitle'] . '">';
            echo '<div class="recipe-info">';
            echo '<h3>' . $row['recipeTitle'] . '</h3>';
            echo '<p>' . substr($row['recipeDescription'], 0, 80) . '...</p>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No recipes available at the moment.</p>';
    }

    mysqli_close($connect);
    ?>
</div>
<a href="index.php" class="all-recipes-btn">Back to Home</a>

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