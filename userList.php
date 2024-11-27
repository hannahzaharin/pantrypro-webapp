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

        /* CSS for Action Buttons (Update, Delete) */
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
    //Call file to connect server eleave
    include ("databaseconnection.php");
?>


<?php 
    //Make the query to fetch product information
    $q = "SELECT userID, userName, userPassword, userEmail, userFullName, userPhoneNo FROM users ORDER BY userID";

    //Run the query and assign it to the variable $result
    $result = @mysqli_query ($connect, $q);

    if ($result) {
        //Table heading
        echo '<table>
        <tr>
            <th>Member ID</th>
            <th>Member Name</th>
            <th>Member Password</th>
            <th>Member Email</th>
            <th>Member Full Name</th>
            <th>Member Phone No</th>
            <th>Actions</th>
        </tr>';

        //Fetch and print all the records
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            echo '<tr>
            <td>'.$row['userID'].'</td>
            <td>'.$row['userName'].'</td>
            <td>'.$row['userPassword'].'</td>
            <td>'.$row['userEmail'].'</td>
            <td>'.$row['userFullName'].'</td>
            <td>'.$row['userPhoneNo'].'</td>
            <td class="action-buttons">
                <form action="recipeUpdate.php" method="get">
                    <input type="hidden" name="id" value="'.$row['userID'].'">
                    <button type="submit" class="update-button">View Profile</button>
                </form>
            </td>
            </tr>';
        }

        //Close the table
        echo '</table>';

        //Free up the resources
        mysqli_free_result ($result);
    } else {
        //Error message
        echo '<p class="error">The member list could not be retrieved. We apologize for any inconvenience.</p>';

        //Debugging message
        echo '<p>'.mysqli_error($connect).'<br><br/>Query:'.$q.'</p>';
    }

    //Close the database connection
    mysqli_close($connect);

?>

</body>
</html>
