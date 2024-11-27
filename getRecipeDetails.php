<?php
include("databaseconnection.php");

if (isset($_GET['recipeID'])) {
    $recipeID = mysqli_real_escape_string($connect, $_GET['recipeID']);
    
    $query = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage FROM recipes WHERE recipeID = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $recipeID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Return the first result as JSON
        echo json_encode($result->fetch_assoc());
    } else {
        // If recipe not found, return an empty JSON object
        echo json_encode([]);
    }

    $stmt->close();
}

$connect->close();
?>
