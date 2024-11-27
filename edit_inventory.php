<?php
include("databaseconnection.php");

// Display the form with data pre-filled (GET request)
if (isset($_GET['inventoryID'])) {
    $inventoryID = $_GET['inventoryID'];
    $query = "SELECT * FROM pantry_inventory WHERE inventoryID = '$inventoryID'";
    $result = mysqli_query($connect, $query);
    $item = mysqli_fetch_assoc($result);

    if ($item) {
        ?>
        <form action="edit_inventory.php" method="post">
            <input type="hidden" name="inventoryID" value="<?php echo htmlspecialchars($inventoryID); ?>">
            
            <label>Ingredient Name:</label>
            <input type="text" name="ingredient_name" value="<?php echo htmlspecialchars($item['ingredient_name']); ?>" required>
            
            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
            
            <label>Stocked Date:</label>
            <input type="date" name="stocked_date" value="<?php echo htmlspecialchars($item['stocked_date']); ?>" required>
            
            <label>Need To Purchase:</label>
            <input type="number" name="to_purchase" value="<?php echo htmlspecialchars($item['to_purchase']); ?>" required>
            
            <button type="submit">Update</button>
        </form>
        <?php
    } else {
        echo "Item not found.";
    }
}
// Handle the update action (POST request)
elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['inventoryID'], $_POST['ingredient_name'], $_POST['quantity'], $_POST['stocked_date'], $_POST['to_purchase'])) {
    $inventoryID = $_POST['inventoryID'];
    $ingredient_name = $_POST['ingredient_name'];
    $quantity = $_POST['quantity'];
    $stocked_date = $_POST['stocked_date'];
    $to_purchase = $_POST['to_purchase'];  // Directly use the posted value

    $query = "UPDATE pantry_inventory SET ingredient_name = '$ingredient_name', quantity = '$quantity', stocked_date = '$stocked_date', to_purchase = '$to_purchase' WHERE inventoryID = '$inventoryID'";
    if (mysqli_query($connect, $query)) {
        // Redirect to the "Manage Pantry Inventory" section on dashboard.php
        header("Location: pantry_inventory.php#manage-inventory");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($connect);    
    }
} else {
    echo "Required data missing for editing.";
}
?>
