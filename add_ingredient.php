<?php 
include("databaseconnection.php");

$recipeID = $_GET['recipeID']; // Recipe ID passed from the register page

// Fetch existing ingredients for this recipe
$existingIngredients = [];
$query = "SELECT i.ingredientName, r.quantity, r.unit 
          FROM recipe_ingredients r 
          JOIN ingredients i ON r.ingredientID = i.ingredientID 
          WHERE r.recipeID = '$recipeID'";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $existingIngredients[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ingredientNames = $_POST['ingredientName']; // Array of ingredient names
    $quantities = $_POST['ingredientQuantity']; // Array of quantities
    $units = $_POST['ingredientUnit']; // Array of units

    // Loop through each ingredient to process them
    foreach ($ingredientNames as $index => $ingredientName) {
        // Escape each ingredient name, quantity, and unit
        $ingredientNameEscaped = mysqli_real_escape_string($connect, $ingredientName);
        $quantity = mysqli_real_escape_string($connect, $quantities[$index]);
        $unit = mysqli_real_escape_string($connect, $units[$index]);

        // Check if the ingredient already exists for this recipe
        $checkQuery = "SELECT ingredientID FROM ingredients WHERE ingredientName = '$ingredientNameEscaped'";
        $checkResult = mysqli_query($connect, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            $row = mysqli_fetch_assoc($checkResult);
            $ingredientID = $row['ingredientID'];
            
            // Optionally update quantity and unit in the ingredients table
            $updateQuery = "UPDATE ingredients SET ingredientQuantity = '$quantity', ingredientUnit = '$unit' WHERE ingredientID = '$ingredientID'";
            mysqli_query($connect, $updateQuery);
        } else {
            // Add new ingredient with quantity and unit
            $q = "INSERT INTO ingredients (ingredientName, ingredientQuantity, ingredientUnit) VALUES ('$ingredientNameEscaped', '$quantity', '$unit')";
            mysqli_query($connect, $q);
            $ingredientID = mysqli_insert_id($connect);
        }

        // Add ingredient to the recipe in the recipe_ingredients table if not already added
        $recipeIngredientCheckQuery = "SELECT * FROM recipe_ingredients WHERE recipeID = '$recipeID' AND ingredientID = '$ingredientID'";
        $recipeIngredientCheckResult = mysqli_query($connect, $recipeIngredientCheckQuery);
        
        if (mysqli_num_rows($recipeIngredientCheckResult) === 0) {
            $q = "INSERT INTO recipe_ingredients (recipeID, ingredientID, quantity, unit) VALUES ('$recipeID', '$ingredientID', '$quantity', '$unit')";
            mysqli_query($connect, $q);
        }
    }

    // Redirect to view the completed recipe
    header("Location: add_steps.php?recipeID=$recipeID");
    exit();
}
?>

<!-- Form for adding ingredients -->
<form action="" method="POST" id="ingredientForm">
    <div id="ingredientContainer">
        <?php foreach ($existingIngredients as $ingredient): ?>
            <div class="ingredient-row">
                <input type="text" name="ingredientName[]" value="<?php echo htmlspecialchars($ingredient['ingredientName']); ?>" required>
                <input type="number" name="ingredientQuantity[]" value="<?php echo htmlspecialchars($ingredient['quantity']); ?>" required>
                <input type="text" name="ingredientUnit[]" value="<?php echo htmlspecialchars($ingredient['unit']); ?>" required>
            </div>
        <?php endforeach; ?>
        <div class="ingredient-row">
            <input type="text" name="ingredientName[]" placeholder="Ingredient Name" required>
            <input type="number" name="ingredientQuantity[]" placeholder="Quantity" required>
            <input type="text" name="ingredientUnit[]" placeholder="Unit" required>
        </div>
    </div>
    <button type="button" onclick="addIngredientRow()">Add Another Ingredient</button>
    <input type="submit" value="Save Ingredients">
</form>

<script>
function addIngredientRow() {
    const container = document.getElementById('ingredientContainer');
    const row = document.createElement('div');
    row.className = 'ingredient-row';
    row.innerHTML = `
        <input type="text" name="ingredientName[]" placeholder="Ingredient Name" required>
        <input type="number" name="ingredientQuantity[]" placeholder="Quantity" required>
        <input type="text" name="ingredientUnit[]" placeholder="Unit" required>
    `;
    container.appendChild(row);
}
</script>
