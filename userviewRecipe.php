<?php
include("databaseconnection.php");

session_start();
$loggedIn = isset($_SESSION['userID']); // Check if the user is logged in

// Check if recipeID is set in the URL
if (!isset($_GET['recipeID'])) {
    echo "Error: Recipe ID not provided.";
    exit; // Stop further execution if recipeID is missing
}

$recipeID = $_GET['recipeID'];

// Fetch recipe details
$recipeQuery = "SELECT * FROM recipes WHERE recipeID = '$recipeID'";
$recipeResult = mysqli_query($connect, $recipeQuery);
$recipe = mysqli_fetch_assoc($recipeResult);

if (!$recipe) {
    echo "Error: Recipe not found.";
    exit;
}

// Track recipe views
if ($loggedIn) {
    $userID = $_SESSION['userID']; // User ID for logged-in user

    // Check if the user has already viewed this recipe
    $viewCheckQuery = "SELECT * FROM recipeviews WHERE userID = '$userID' AND recipeID = '$recipeID'";
    $viewCheckResult = mysqli_query($connect, $viewCheckQuery);

    if (mysqli_num_rows($viewCheckResult) == 0) {
        // If the user hasn't viewed the recipe, insert a new view record
        $viewQuery = "INSERT INTO recipeviews (userID, recipeID) VALUES ('$userID', '$recipeID')";
        mysqli_query($connect, $viewQuery);
    }
} else {
    // If the user is not logged in, track the view without associating it with a user
    $viewQuery = "INSERT INTO recipeviews (userID, recipeID) VALUES (NULL, '$recipeID')";
    mysqli_query($connect, $viewQuery);
}


// Check if the recipe is already saved by the logged-in user
$isSaved = false;
if ($loggedIn) {
    $checkSavedQuery = "SELECT * FROM saved_recipes WHERE userID = '" . $_SESSION['userID'] . "' AND recipeID = '$recipeID'";
    $checkSavedResult = mysqli_query($connect, $checkSavedQuery);
    $isSaved = mysqli_num_rows($checkSavedResult) > 0;
}

// Fetch ingredients, steps, and ratings (your existing queries)
$ingredientsQuery = "SELECT i.ingredientName, ri.quantity, ri.unit FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredientID = i.ingredientID WHERE ri.recipeID = '$recipeID'";
$ingredientsResult = mysqli_query($connect, $ingredientsQuery);

$stepsQuery = "SELECT * FROM tutorial_steps WHERE recipeID = '$recipeID' ORDER BY stepNumber";
$stepsResult = mysqli_query($connect, $stepsQuery);

$averageRatingQuery = "SELECT AVG(rating) as averageRating FROM recipe_ratings WHERE recipeID = '$recipeID'";
$averageRatingResult = mysqli_query($connect, $averageRatingQuery);
$averageRatingData = mysqli_fetch_assoc($averageRatingResult);
$averageRating = $averageRatingData['averageRating'] ? round($averageRatingData['averageRating'], 1) : 0;

// Fetch the total number of views for this recipe
$viewsQuery = "SELECT COUNT(*) as totalViews FROM recipeviews WHERE recipeID = '$recipeID'";
$viewsResult = mysqli_query($connect, $viewsQuery);
$viewsData = mysqli_fetch_assoc($viewsResult);
$totalViews = $viewsData['totalViews'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['recipeTitle']); ?> - Recipe</title>
    <style>
        /* General Styling */
        body {
    background-color: #fafafa;
    color: #444;
    font-family: 'Segoe UI', Tahoma, Geneva, sans-serif;
}

.container {
    max-width: 800px;
    margin: auto;
    padding: 20px;
}

.recipe-header {
    position: relative;
    text-align: center;
}
        .recipe-header h1 {
            margin: 0;
        }
        .recipe-image {
    width: 100%;
    border-radius: 10px;
    object-fit: cover;
    max-height: 300px;
    opacity: 0.9;
}

.recipe-title {
    font-size: 2rem;
    color: #333;
    margin-top: 10px;
    font-weight: bold;
}
.section {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
    margin-top: 20px;
}

.section h2 {
    font-size: 1.3em;
    color: #6abf69;
    margin-bottom: 10px;
}

.ingredient-list, .steps-list {
    list-style-type: none;
    padding: 0;
}

.ingredient-list li, .steps-list li {
    padding: 8px 0;
    display: flex;
    justify-content: space-between;
}

        
        /* Steps */
        .steps-list {
            list-style: none;
            padding: 0;
        }
        .step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .step-number {
            background-color: #4CAF50;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        .step-instruction {
            flex: 1;
        }
        .step-duration {
            font-size: 0.85em;
            color: #888;
            text-align: right;
        }

        /* Button Styling */
        .button {
    background-color: #6abf69;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1em;
    text-align: center;
    transition: 0.3s;
}

.button:hover {
    background-color: #5ca25c;
}
        .add-to-favorites {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #388e3c; /* Green background */
            color: #ffffff; /* White text */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            float: right; /* Align button to the right */
            margin-top: 20px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
        }

        .add-to-favorites:hover {
            background-color: #2e7d32; /* Darker green on hover */
        }

        .star-icon {
            font-size: 1.2em;
            color: #d4e157; /* Bright yellow-green star */
            margin-right: 8px;
        }


.rating-stars span {
    font-size: 2em;
    cursor: pointer;
    color: #ccc;
}

.rating-stars span.selected {
    color: #FFD700; /* Gold color for selected stars */
}

/* CSS for Toast Notification */
.toast-notification {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
        }
        .toast-notification.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }
        @-webkit-keyframes fadein {
            from { bottom: 0; opacity: 0; }
            to { bottom: 30px; opacity: 1; }
        }
        @keyframes fadein {
            from { bottom: 0; opacity: 0; }
            to { bottom: 30px; opacity: 1; }
        }
        @-webkit-keyframes fadeout {
            from { bottom: 30px; opacity: 1; }
            to { bottom: 0; opacity: 0; }
        }
        @keyframes fadeout {
            from { bottom: 30px; opacity: 1; }
            to { bottom: 0; opacity: 0; }
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

<!-- Always Visible Floating Back to Top Button -->
<button id="floatingBackToTop" title="Back to Top">⬆️</button>

<div class="container">
    <!-- Recipe Header -->
    <div class="recipe-header">
        <!-- Recipe Image -->
        <img src="<?php echo htmlspecialchars($recipe['recipeImage']); ?>" alt="<?php echo htmlspecialchars($recipe['recipeTitle']); ?>" class="recipe-image">
        
        <!-- Recipe Title and Description -->
        <h1><?php echo htmlspecialchars($recipe['recipeTitle']); ?></h1>
        <p><?php echo htmlspecialchars($recipe['recipeDescription']); ?></p>
    </div>

    <!-- Average Rating Section -->
    <div class="section">
        <h2>Average Rating</h2>
        <p><?php echo $averageRating; ?> / 5</p>
    </div>

    <!-- Ingredients Section -->
    <div class="section">
        <h2>Ingredients</h2>
        <ul class="ingredient-list">
            <?php while ($ingredient = mysqli_fetch_assoc($ingredientsResult)) { ?>
                <li>
                    <span><?php echo htmlspecialchars($ingredient['ingredientName']); ?></span>
                    <span><?php echo htmlspecialchars($ingredient['quantity'] . " " . $ingredient['unit']); ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Steps Section -->
    <div class="section">
        <h2>Instructions</h2>
        <ul class="steps-list">
            <?php while ($step = mysqli_fetch_assoc($stepsResult)) { ?>
                <li class="step">
                    <div class="step-number"><?php echo htmlspecialchars($step['stepNumber']); ?></div>
                    <div class="step-instruction"><?php echo htmlspecialchars($step['stepInstruction']); ?></div>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="section">
    <h2>Rate this Recipe</h2>
    <div class="rating-stars" id="rating-stars">
        <span data-rating="1">★</span>
        <span data-rating="2">★</span>
        <span data-rating="3">★</span>
        <span data-rating="4">★</span>
        <span data-rating="5">★</span>
    </div>
    <button class="button" onclick="submitRating()">Submit Rating</button>
    <span id="ratingDisplay"></span> <!-- Display rating here -->
</div>


<a href="#" class="add-to-favorites" id="add-to-favorites">
    <span class="star-icon">★</span> <?php echo $isSaved ? 'Remove from Favorites' : 'Add to Favorites'; ?>
</a>

</div>


<script>
const stars = document.querySelectorAll('#rating-stars span');
let selectedRating = 0;

stars.forEach(star => {
    // When mouse enters a star, highlight it and all previous stars
    star.addEventListener('mouseover', function () {
        let hoveredRating = this.getAttribute('data-rating');
        
        // Reset all stars to their default color
        stars.forEach(s => {
            s.classList.remove('selected');
        });
        
        // Highlight the hovered star and all previous stars
        stars.forEach((s, index) => {
            if (index < hoveredRating) {
                s.classList.add('selected');
            }
        });
    });

    // When clicking on a star, set the selected rating and highlight the appropriate stars
    star.addEventListener('click', function () {
        selectedRating = this.getAttribute('data-rating');
        stars.forEach((s, index) => {
            if (index < selectedRating) {
                s.classList.add('selected');
            } else {
                s.classList.remove('selected');
            }
        });
    });

    // When mouse leaves the stars, reset to the selected rating (if any)
    star.addEventListener('mouseout', function () {
        stars.forEach((s, index) => {
            if (index < selectedRating) {
                s.classList.add('selected');
            } else {
                s.classList.remove('selected');
            }
        });
    });
});

function submitRating() {
    if (selectedRating === 0) {
        alert('Please select a rating.');
        return;
    }

    fetch('submit_rating.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `recipeID=${<?php echo $recipeID; ?>}&rating=${selectedRating}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for your rating!');
        } else {
            alert('There was an error submitting your rating.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function submitRating() {
    if (selectedRating === 0) {
        alert('Please select a rating.');
        return;
    }

    // Send AJAX request
    fetch('submit_rating.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `recipeID=${<?php echo json_encode($recipeID); ?>}&rating=${selectedRating}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thank you for your rating!');
        } else {
            alert('There was an error submitting your rating.');
        }
    })
    .catch(error => console.error('Error:', error));
}



document.getElementById('add-to-favorites').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior
    
    // If logged in, make an AJAX request to add/remove the recipe from saved list
    if (<?php echo $loggedIn ? 'true' : 'false'; ?>) {
        fetch('save_recipe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `recipeID=${<?php echo $recipeID; ?>}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Display a message (e.g., "Added to favorites" or "Removed from favorites")
            location.reload(); // Reload the page to reflect the change
        })
        .catch(error => console.error('Error:', error));
    } else {
        window.location.href = 'login.php'; // If not logged in, redirect to login
    }
});

 // Smooth scroll to the top when the button is clicked
 document.getElementById("floatingBackToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};


</script>
</body>
</html>
