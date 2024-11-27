<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PantryPro - Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Existing styles for Admin Dashboard */
        /* ... (existing CSS here) ... */

        /* Custom styles for Recipe List */

        /* Admin Dashboard Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            color: #333;
        }

        .dashboard-header {
            background-color: #2e7d32;
            padding: 20px;
            color: white;
            text-align: center;
        }

        .dashboard-header h1 {
            margin: 0;
        }

        .dashboard-container {
            display: flex;
            margin: 20px;
        }

        .sidebar {
            background-color: #ffffff;
            width: 250px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #2e7d32;
            font-weight: 600;
        }

        .sidebar ul li a:hover {
            color: #1e5631;
        }

        .main-content {
            flex-grow: 1;
            background-color: #ffffff;
            padding: 20px;
            margin-left: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            flex: 1;
            background-color: #2e7d32;
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h2 {
            margin-top: 0;
        }

        .table-section {
            margin-top: 20px;
            background-color: #ffffff; /* Ensure the background is white */
            padding: 20px; /* Add padding to the section */
            border-radius: 8px; /* Round the corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #2e7d32; /* Green border */
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #2e7d32; /* Green for header */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #e8f5e9; /* Light green for even rows */
        }

        tr:hover {
            background-color: #c8e6c9; /* Light green for hover effect */
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            color: white;
        }

        .edit-btn {
            background-color: #4caf50; /* Green for edit button */
        }

        .delete-btn {
            background-color: #ff4c4c; /* Red for delete button */
        }

        .edit-btn:hover {
            background-color: #388e3c; /* Darker green on hover */
        }

        .delete-btn:hover {
            background-color: #e60000; /* Darker red on hover */
        }

        .view-details-btn {
    background-color: #2e7d32;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    text-decoration: none;
}

.view-details-btn:hover {
    background-color: #1e5631;
}

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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #388e3c;
            color: white;
            border: 1px solid #388e3c;
        }

        tr:nth-child(even) {
            background-color: #e8f5e9;
        }

        tr:nth-child(odd) {
            background-color: #f1f8e9;
        }

        tr:hover {
            background-color: #c8e6c9;
        }

        td {
            border: 1px solid #388e3c;
        }

        .action-buttons {
            display: flex;
            justify-content: space-evenly;
        }

        .view-button, .update-button, .delete-button {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .view-button { background-color: #2196f3; }
        .update-button { background-color: #4caf50; }
        .delete-button { background-color: #f44336; }

        .view-button:hover { background-color: #1976d2; }
        .update-button:hover { background-color: #388e3c; }
        .delete-button:hover { background-color: #d32f2f; }

        #toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #4caf50;
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

        #toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }

        /* Modal Background */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

/* Modal Content */
.modal-content {
  background-color: #fff;
  margin: 15% auto;
  padding: 20px;
  border-radius: 5px;
  width: 80%;
  max-width: 400px;
  text-align: center;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
}

.confirm-btn {
  background-color: red;
  color: white;
  border: none;
  padding: 10px 20px;
  margin: 10px;
  cursor: pointer;
  border-radius: 5px;
}

.cancel-btn {
  background-color: grey;
  color: white;
  border: none;
  padding: 10px 20px;
  margin: 10px;
  cursor: pointer;
  border-radius: 5px;
}

.confirm-btn:hover, .cancel-btn:hover {
  opacity: 0.8;
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

<div id="toast">Recipe successfully deleted!</div>

    <script>
        // Function to show the toast message
        function showToast() {
            const toast = document.getElementById("toast");
            toast.className = "show";
            setTimeout(() => {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }

        // Check if the URL contains 'deleted=true'
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('deleted') === 'true') {
            showToast();

            // Remove 'deleted=true' from the URL without reloading the page
            urlParams.delete('deleted');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Confirm Deletion</h2>
    <p>Are you sure you want to delete this recipe? This action cannot be undone.</p>
    <button onclick="confirmDelete()" class="confirm-btn">Delete</button>
    <button onclick="closeModal()" class="cancel-btn">Cancel</button>
  </div>
</div>

 <!-- Always Visible Floating Back to Top Button -->
 <button id="floatingBackToTop" title="Back to Top">⬆️</button>

    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <h1>Admin Dashboard - PantryPro</h1>
    </header>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <ul>
                <li><a href="#overview">Overview</a></li>
                <li><a href="#users">Manage Members</a></li>
                <li><a href="#recipes">Manage Recipes</a></li>
                <li><a href="#reviews">View Reviews</a></li>
                <li><a href="#reports">Reports</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Overview Cards -->
            <div class="card-container">
                <div class="card">
                    <h2>Total Users</h2>
                    <p>1,250</p>
                </div>
                <div class="card">
                    <h2>Total Recipes</h2>
                    <p>450</p>
                </div>
                <div class="card">
                    <h2>Active Reviews</h2>
                    <p>2,300</p>
                </div>
            </div>

            <!-- Manage Users Section -->
            <div id="users" class="table-section">
                <h1>Manage Members</h2>
                <?php
                include('userList.php');
                ?>
            </div>


        <!-- Main Content -->
        <div class="main-content">
        <section id="recipes">
            <h2>Recipe List</h2>

            <!-- Recipe Search Form -->
            <div class="search-product-form">
                <form action="admin_dashboard.php#recipes" method="post">
                    <label for="recipeTitle">Menu Name:</label>
                    <input id="recipeTitle" type="text" name="recipeTitle" size="50" maxLength="100" value="<?php if (isset($_POST['recipeTitle'])) echo $_POST['recipeTitle']; ?>"/>
                    <input id="submit" type="submit" name="submit" value="Search"/>
                </form>
            </div>

            <?php
            // Include the database connection file
            include ("databaseconnection.php");

            // Fetch the search term from the form and query recipes
            if (isset($_POST['submit']) && !empty($_POST['recipeTitle'])) {
                $searchTitle = mysqli_real_escape_string($connect, $_POST['recipeTitle']);
                $q = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage, recipeCreated, recipeCategory 
                      FROM recipes WHERE recipeTitle LIKE '%$searchTitle%' ORDER BY recipeID";
            } else {
                $q = "SELECT recipeID, recipeTitle, recipeDescription, recipeImage, recipeCreated, recipeCategory
                      FROM recipes ORDER BY recipeID";
            }

            // Run the query and check for results
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
                    <th>Category</th>
                    <th>Actions</th>
                </tr>';

                // Display the records
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<tr>
                    <td>' . $row['recipeID'] . '</td>
                    <td>' . $row['recipeTitle'] . '</td>
                    <td>' . $row['recipeDescription'] . '</td>
                    <td><img src="' . $row['recipeImage'] . '" alt="Recipe Image" width="100"></td>
                    <td>' . date('Y-m-d', strtotime($row['recipeCreated'])) . '</td>
                    <td>' .$row['recipeCategory'] . '</td>
                    <td class="action-buttons">
                        <form action="viewRecipe.php" method="get" style="display:inline;">
                            <input type="hidden" name="recipeID" value="' . $row['recipeID'] . '">
                            <button type="submit" class="view-button">View Details</button>
                        </form>
                        <form action="recipeUpdate.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="' . $row['recipeID'] . '">
                            <button type="submit" class="update-button">Update</button>
                        </form>
                    </td>
                    </tr>';
                }

                // Close the table
                echo '</table>';

                // Free the result
                mysqli_free_result($result);
            } else {
                echo '<p class="error">The recipe list could not be retrieved. We apologize for any inconvenience.</p>';
            }

            // Close the database connection
            mysqli_close($connect);
            ?>

<script>
                function deleteRecipe(recipeID) {
                    if (confirm('Are you sure you want to delete this recipe?')) {
                        window.location.href = 'delete_recipe.php?id=' + recipeID + '&deleted=true';
                    }
                }

                 // Smooth scroll to the top when the button is clicked
document.getElementById("floatingBackToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
            </script>

            <div class="add-product-button">
                <a href="recipeRegister.php" style="text-decoration: none;">
                    <button>Add Menu</button>
                </a>
            </div>
        </section>

             <!-- Reports Section -->
             <div id="reports" class="table-section">
                <h2>Reports</h2>
                <p>Coming Soon...</p>
            </div>
        </div>
    </div>
</body>
</html>
