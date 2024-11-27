<?php
include("databaseconnection.php");

if (isset($_POST['inventoryID'])) {
    $inventoryID = $_POST['inventoryID'];

    $query = "DELETE FROM pantry_inventory WHERE inventoryID = '$inventoryID'";
    if (mysqli_query($connect, $query)) {
        header("Location: pantry_inventory.php#manage-inventory");
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($connect);
    }
} else {
    echo "ID not provided for deletion.";
}
?>
