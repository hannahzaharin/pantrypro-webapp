<?php
session_start();
include("databaseconnection.php");

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

// Retrieve the user's ID from the session
$userID = $_SESSION['userID'];

// Check if the form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ingredientName = mysqli_real_escape_string($connect, $_POST['ingredient_name']);
    $quantity = (int)$_POST['quantity'];
    $stocked_date = mysqli_real_escape_string($connect, $_POST['stocked_date']);
    $to_purchase = (int)$_POST['to_purchase'];

    // Validate the form data
    if (empty($ingredientName) || $quantity <= 0 || empty($stocked_date)) {
        echo "Please fill out all fields correctly.";
    } else {
        // Insert the ingredient into the pantry_inventory table
        $query = "INSERT INTO pantry_inventory (userID, ingredient_name, quantity, stocked_date, to_purchase) 
                  VALUES ('$userID', '$ingredientName', '$quantity', '$stocked_date', '$to_purchase')";

        if (mysqli_query($connect, $query)) {
            // Redirect with a success message
            header("Location: dashboard.php#manage-inventory");
            exit;
        } else {
            echo "Error: " . mysqli_error($connect);
        }
    }
} else {
    echo "Invalid request method.";
}
?>
