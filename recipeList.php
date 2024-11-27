<html>
<head>
    <title>Recipe List</title>
    
    <style>
        /* CSS for Product List Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #388e3c; /* Dark Green */
            color: white;
            border: 1px solid #388e3c;
        }

        tr:nth-child(even) {
            background-color: #e8f5e9; /* Light Green */
        }

        tr:nth-child(odd) {
            background-color: #f1f8e9; /* Lighter Green */
        }

        tr:hover {
            background-color: #c8e6c9; /* Green hover effect */
        }

        td {
            border: 1px solid #388e3c;
        }

        /* CSS for Action Buttons (Update, Delete, View Details) */
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
        }

        .action-buttons button {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
        }

        .view-button {
            background-color: #2196f3; /* Blue Button */
            color: white;
        }

        .view-button:hover {
            background-color: #1976d2; /* Darker Blue on hover */
        }

        .update-button {
            background-color: #4caf50; /* Green Button */
            color: white;
        }

        .update-button:hover {
            background-color: #388e3c; /* Dark Green on hover */
        }

        .delete-button {
            background-color: #f44336; /* Red Button */
            color: white;
        }

        .delete-button:hover {
            background-color: #d32f2f; /* Dark Red on hover */
        }

        /* CSS for Add Product Button */
        .add-product-button {
            text-align: right;
            margin-top: 20px;
            margin-right: 20px;
        }

        .add-product-button button {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4caf50; /* Green Button */
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .add-product-button button:hover {
            background-color: #388e3c; /* Dark Green on hover */
        }

        /* CSS for Search Product Form */
        .search-product-form {
            text-align: center;
            margin-top: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 60%;
            border: 2px solid #4caf50;
            border-radius: 5px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #388e3c;
        }

        h2 {
            color: #388e3c;
            text-align: center;
            margin-bottom: 20px;
        }

    </style>
</head>

<body>

<?php
    // Include the database connection file
    include ("databaseconnection.php");
?>

<div class="search-product-form">
    <form action="recipeList.php" method="post">
        <p>
            <label class="label" for="recipeTitle">Menu Name:</label>
            <input id="recipeTitle" type="text" name="recipeTitle" size="50" maxLength="100" value="<?php if (isset($_POST['recipeTitle'])) echo $_POST['recipeTitle']; ?>"/>
        </p>
        <input id="submit" type="submit" name="submit" value="Search"/>
    </form>
</div>

<?php 
    // Fetch the search term from the form
    if (isset($_POST['submit']) && !empty($_POST['recipeTitle'])) {
        $searchTitle = mysqli_real_escape_string($connect, $_POST['recipeTitle']);
        // Query to search for recipes based on title
        $q = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage, recipeCreated, cooking_method 
              FROM recipes WHERE recipeTitle LIKE '%$searchTitle%' ORDER BY recipeID";
    } else {
        // Default query to display all recipes
        $q = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage, recipeCreated, cooking_method 
              FROM recipes ORDER BY recipeID";
    }

    // Run the query and assign it to the variable $result
    $result = @mysqli_query($connect, $q);

    if ($result) {
        // Table heading
        echo '<table>
        <tr>
            <th>Recipe ID</th>
            <th>Title Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Date Created</th>
            <th>Cooking Method</th>
            <th>Actions</th>
        </tr>';

        // Fetch and print all the records
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<tr>
            <td>'.$row['recipeID'].'</td>
            <td>'.$row['recipeTitle'].'</td>
            <td>'.$row['recipeDescription'].'</td>
            <td><img src="'.$row['recipeImage'].'" alt="Product Image" width="100"></td>
            <td>'.date('Y-m-d', strtotime($row['recipeCreated'])).'</td>
            <td>'.$row['cooking_method'].'</td>
            <td class="action-buttons">
                <form action="viewRecipe.php" method="get" style="display:inline;">
                    <input type="hidden" name="recipeID" value="'.$row['recipeID'].'">
                    <button type="submit" class="view-button">View Details</button>
                </form>
                <form action="recipeUpdate.php" method="get" style="display:inline;">
                    <input type="hidden" name="id" value="'.$row['recipeID'].'">
                    <button type="submit" class="update-button">Update</button>
                </form>
                <form action="recipeDelete.php" method="get" style="display:inline;">
                    <input type="hidden" name="id" value="'.$row['recipeID'].'">
                    <button type="submit" class="delete-button">Delete</button>
                </form>
            </td>
            </tr>';
        }

        // Close the table
        echo '</table>';

        // Free up the resources
        mysqli_free_result($result);
    } else {
        // Error message if no recipes are found
        echo '<p class="error">The recipe list could not be retrieved. We apologize for any inconvenience.</p>';
    }

    // Close the database connection
    mysqli_close($connect);
?>

<div class="add-product-button">
    <a href="recipeRegister.php" style="text-decoration: none;">
        <button>Add Menu</button>
    </a>
</div>

</body>
</html>
