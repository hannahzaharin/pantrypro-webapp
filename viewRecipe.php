<?php
include("databaseconnection.php");

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

// Fetch ingredients
$ingredientsQuery = "
    SELECT i.ingredientName, ri.quantity, ri.unit 
    FROM recipe_ingredients ri 
    JOIN ingredients i ON ri.ingredientID = i.ingredientID 
    WHERE ri.recipeID = '$recipeID'";
$ingredientsResult = mysqli_query($connect, $ingredientsQuery);

// Fetch steps
$stepsQuery = "SELECT * FROM tutorial_steps WHERE recipeID = '$recipeID' ORDER BY stepNumber";
$stepsResult = mysqli_query($connect, $stepsQuery);
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
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }

        /* Recipe Header */
        .recipe-header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .recipe-header h1 {
            margin: 0;
        }
        .recipe-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        /* Sections */
        .section {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            color: #4CAF50;
            font-size: 1.2em;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* Ingredient List */
        .ingredient-list {
            list-style-type: none;
            padding: 0;
        }
        .ingredient-list li {
            padding: 5px 0;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
        }

        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Button Styling */
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #45a049;
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Recipe Header -->
    <div class="recipe-header">
        <!-- Recipe Image -->
        <img src="<?php echo htmlspecialchars($recipe['recipeImage']); ?>" alt="<?php echo htmlspecialchars($recipe['recipeTitle']); ?>" class="recipe-image">
        
        <!-- Recipe Title and Description -->
        <h1><?php echo htmlspecialchars($recipe['recipeTitle']); ?></h1>
        <p><?php echo htmlspecialchars($recipe['recipeDescription']); ?></p>
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

    <!-- Edit Buttons -->
    <div class="section" style="text-align: center;">
        <button class="button" onclick="window.location.href='add_ingredient.php?recipeID=<?php echo $recipeID; ?>'">Edit Ingredients</button>
        <button class="button" onclick="window.location.href='add_steps.php?recipeID=<?php echo $recipeID; ?>'">Edit Steps</button>
    </div>

    <!-- Delete Button -->
<!-- Delete Button -->
<button class="button" onclick="openModal(<?php echo $recipeID; ?>)">Delete Recipe</button>

<!-- Modal for Delete Confirmation -->
<div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Are you sure you want to delete this recipe?</h2>
            <p>This action cannot be undone.</p>
            <button class="button" id="confirmDeleteBtn">Delete</button>
            <button class="button" onclick="closeModal()">Cancel</button>
        </div>
    </div>

            </div>

<script>
// Open the modal
function openModal(recipeID) {
    document.getElementById('deleteModal').style.display = 'block';
    
    // Set the delete button's onclick event to delete the recipe
    document.getElementById('confirmDeleteBtn').onclick = function() {
        window.location.href = 'viewrecipeDelete.php?recipeID=' + recipeID;
    };
}

// Close the modal
function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close the modal if the user clicks anywhere outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById('deleteModal')) {
        closeModal();
    }
}
</script>

</body>
</html>