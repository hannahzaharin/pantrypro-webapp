<html>
<head>
    <title>Delete Recipe Record</title>
</head>
<body>
    <?php
    // Connect to the database
    include("databaseconnection.php");

    echo '<h2>Delete Recipe Record</h2>';

    // Validate recipe ID from GET or POST
    if ((isset($_GET['id']) && is_numeric($_GET['id']))) {
        $id = $_GET['id'];
    } elseif ((isset($_POST['id']) && is_numeric($_POST['id']))) {
        $id = $_POST['id'];
    } else {
        echo '<p class="error">This page has been accessed in error.</p>';
        exit();
    }

    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['sure'] === 'Yes') { // Delete record if confirmed
            $q = "DELETE FROM recipes WHERE recipeID = $id LIMIT 1";
            $result = @mysqli_query($connect, $q);

            if (mysqli_affected_rows($connect) === 1) {
                // Successful deletion
                echo '<script>alert("The recipe has been deleted successfully."); window.location.href="recipeList.php";</script>';
            } else {
                // Failed deletion
                echo '<p class="error">The record could not be deleted due to a system error or because it does not exist.</p>';
                echo '<p>' . mysqli_error($connect) . '<br>Query: ' . $q . '</p>';
            }
        } else {
            echo '<script>alert("The recipe has NOT been deleted."); window.location.href="recipeList.php";</script>';
        }
    } else {
        // Display confirmation form
        $q = "SELECT recipeTitle FROM recipes WHERE recipeID = $id";
        $result = @mysqli_query($connect, $q);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_array($result, MYSQLI_NUM);
            echo "<h3>Are you sure you want to permanently delete the recipe: $row[0]?</h3>";
            echo '<form action="recipeDelete.php" method="post">
                    <input type="submit" name="sure" value="Yes">
                    <input type="submit" name="sure" value="No">
                    <input type="hidden" name="id" value="' . $id . '">
                  </form>';
        } else {
            echo '<p class="error">This page has been accessed in error.</p>';
        }
    }

    mysqli_close($connect);
    ?>
</body>
</html>
