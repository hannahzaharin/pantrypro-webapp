<?php
session_start();
include("databaseconnection.php");

// Define function to fetch pantry inventory based on userID
function getPantryInventory($userID) {
    global $connect;
    $query = "SELECT inventoryID, ingredient_name, quantity, stocked_date, to_purchase FROM pantry_inventory WHERE userID = '$userID'";
    $result = mysqli_query($connect, $query);
    
    $inventory = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $inventory[] = $row;
    }

    return $inventory;
}

// Retrieve the userID from session or set a default for testing
$userID = $_SESSION['userID'] ?? 1; // Replace 1 with a default value or session value
$pantryInventory = getPantryInventory($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Pantry Inventory</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #ffffff; /* Set background to white */
            color: black; /* Set font color to green */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

header {
    background-color: #A8D5BA; /* Light soft green color */
    padding: 10px;
}

.nav-links {
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
}

.nav-links > li {
    position: relative;
    margin-right: 20px; /* Space between menu items */
}

.nav-links a {
    text-decoration: none;
    padding: 10px;
    color: black; /* Text color */
}

        /* Dashboard Specific Styles */
        .dashboard-container {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-header h1 {
            color: #A8D5BA;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .inventory-table th, .inventory-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .inventory-table th {
            background-color: #2e7d32;
            color: white;
        }
        /* Footer */
        footer {
            background-color: #A8D5BA;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 50px;
        }

        /* Modal background (overlay) */
        .modal {
            cursor: default;  /* Set to arrow cursor when hovering over the modal overlay */
        }

        /* Modal content (form container) */
        .modal-content {
            cursor: default;  /* Set to arrow cursor when hovering over the modal content */
        }

        /* Input fields inside the modal (where typing is allowed) */
        input, textarea {
            cursor: text; /* Text cursor when hovering over input fields */
        }

        /* Styles for the search container and suggestions dropdown */
        .search-container {
            position: relative;
            max-width: 800px;
            margin: 5px auto;
        }

        #recipe-search {
            width: 100%;
            padding: 8px;
            font-size: 14px;
        }

        .suggestions-container {
            position: absolute;
            background-color: #ffffff;
            border: 1px solid #ddd;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1;
            color: #333;
        }

        .suggestion-item {
            padding: 6px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }

        .no-results {
            padding: 6px;
            color: #666;
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
      <!-- Header -->
<header class="hero-section">
    <nav>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#">My Pantry</a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="grocery_list.php">Grocery List</a></li>
            <li><a href="meal_planner.php">Meal Planner</a></li>

                <div class="search-container">
    <input type="text" id="recipe-search" placeholder="Search for recipes..." onkeyup="showSuggestions(this.value)">
    <div id="suggestions" class="suggestions-container"></div>
</div>
                <!-- Logout Button -->
                <a href="logout.php" class="logout-button" style="margin-left: 5px; margin-right: 20px; background-color: #A8D5BA; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Logout</a>
            </div>
            </div>
        </ul>
    </nav>
    </header>
   

    <!-- Recommendation for New Feature -->
<section id="new-feature-recommendation" style="background-color: #FFEB3B; color: #333; padding: 15px; text-align: center; margin-bottom: 20px;">
    <p style="font-size: 18px; font-weight: bold;">
        Try our new <span style="color: #2e7d32;">"Generate Grocery List"</span> feature! Click below to get started.
    </p>
    <button onclick="window.location.href='grocery_list.php'" 
            style="background-color: #2e7d32; color: #ffffff; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
        Generate Grocery List
    </button>
</section>


<!-- Manage Pantry Inventory Section -->
<section id="manage-inventory">
    <div class="content">
        <h2>Manage Pantry Inventory</h2>
        <button id="openFormButton">Add New Ingredient</button>

    </div>
</section>

 <!-- Always Visible Floating Back to Top Button -->
 <button id="floatingBackToTop" title="Back to Top">⬆️</button>

<!-- Popup Form Modal -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-button" id="closeFormButton">&times;</span>
        <h3>Add Ingredient</h3>
        <form method="POST" action="add_inventory.php">
            <input type="text" name="ingredient_name" placeholder="Ingredient" required>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="date" name="stocked_date" required>
            <input type="number" name="to_purchase" placeholder="Need to Purchase" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<!-- Pantry Inventory Table -->
<table class="inventory-table">
    <thead>
        <tr>
            <th>Ingredient</th>
            <th>Quantity</th>
            <th>Stocked Date</th>
            <th>Need To Purchase</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT * FROM pantry_inventory WHERE userID = '$userID'";
    $result = mysqli_query($connect, $query);
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ingredient_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['stocked_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['to_purchase']) . "</td>";
        echo "<td>
                <button type='button' onclick='openEditModal(" . $row['inventoryID'] . ")'>Edit</button>
                <button type='button' onclick='openModal(" . $row['inventoryID'] . ")'>Delete</button>
              </td>";
        echo "</tr>";
    }
    ?>
</tbody>
</table>
</div>
</section>

<!-- Edit Inventory Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fff; padding: 20px; border-radius: 5px; max-width: 400px; margin: 100px auto; text-align: center;">
        <h3>Edit Inventory Item</h3>
        <form id="editForm" method="post" action="edit_inventory.php">
            <input type="hidden" name="inventoryID" id="editInventoryID">
            
            <label>Ingredient Name:</label>
            <input type="text" name="ingredient_name" id="editIngredientName" required>
            
            <label>Quantity:</label>
            <input type="number" name="quantity" id="editQuantity" required>
            
            <label>Stocked Date:</label>
            <input type="date" name="stocked_date" id="editStockedDate" required>
            
            <label>Need To Purchase:</label>
            <input type="number" name="to_purchase" id="editToPurchase" required>
            
            <button type="submit">Update</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>


<!-- Delete Inventory Confirmation Modal -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div style="background-color: #fff; padding: 20px; border-radius: 5px; max-width: 400px; margin: 100px auto; text-align: center;">
        <h3>Are you sure you want to delete this item?</h3>
        <form action="delete_inventory.php" method="post">
            <input type="hidden" name="inventoryID" id="deleteInventoryID">
            <button type="submit">Yes, Delete</button>
            <button type="button" onclick="closeDeleteModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
// Open the modal when the "Add New Ingredient" button is clicked
document.getElementById('openFormButton').onclick = function() {
    document.getElementById('formModal').style.display = 'block';
}

// Close the modal when the close button is clicked
document.getElementById('closeFormButton').onclick = function() {
    document.getElementById('formModal').style.display = 'none';
}

// Close the modal if the user clicks anywhere outside of the modal content
window.onclick = function(event) {
    var modal = document.getElementById('formModal');
    // Check if the user clicked outside the modal content
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

// Function to open the Edit modal and populate fields
function openEditModal(inventoryID) {
    // Fetch item details using AJAX
    fetch(`edit_inventory.php?inventoryID=${inventoryID}`)
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            
            // Fill in the form fields from the returned HTML
            document.getElementById('editInventoryID').value = inventoryID;
            document.getElementById('editIngredientName').value = doc.querySelector("input[name='ingredient_name']").value;
            document.getElementById('editQuantity').value = doc.querySelector("input[name='quantity']").value;
            document.getElementById('editStockedDate').value = doc.querySelector("input[name='stocked_date']").value;
            document.getElementById('editToPurchase').value = doc.querySelector("input[name='to_purchase']").value;
            
            // Show the modal
            document.getElementById('editModal').style.display = 'block';
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Function to open the Delete confirmation modal
function openModal(inventoryID) {
    // Set the inventoryID for deletion
    document.getElementById('deleteInventoryID').value = inventoryID;
    
    // Show the modal
    document.getElementById('deleteModal').style.display = 'block';
}

// Function to close the Edit modal
function closeEditModal() {
    // Hide the modal
    document.getElementById('editModal').style.display = 'none';
}

// Function to close the Delete modal
function closeDeleteModal() {
    // Hide the modal
    document.getElementById('deleteModal').style.display = 'none';
}

// Close the modal if the user clicks outside the modal content
window.onclick = function(event) {
    // Close the Edit modal if clicked outside
    if (event.target == document.getElementById('editModal')) {
        closeEditModal();
    }

    // Close the Delete modal if clicked outside
    if (event.target == document.getElementById('deleteModal')) {
        closeDeleteModal();
    }
    
};
    document.getElementById("searchInput").addEventListener("input", function () {
        let query = this.value;
        if (query.length > 1) { // Start searching after typing 2+ characters
            fetch(`searchRecipes.php?ingredient=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let searchResults = document.getElementById("searchResults");
                    searchResults.innerHTML = "";
                    searchResults.style.display = "block";
                    
                    if (data.length > 0) {
                        data.forEach(recipe => {
                            let resultDiv = document.createElement("div");
                            resultDiv.innerHTML = `<input type="checkbox" name="selected_recipes[]" value="${recipe.recipeID}"> ${recipe.recipeTitle}`;
                            searchResults.appendChild(resultDiv);
                        });
                    } else {
                        let noResults = document.createElement("div");
                        noResults.textContent = "No recipes found.";
                        searchResults.appendChild(noResults);
                    }
                });
        } else {
            document.getElementById("searchResults").style.display = "none";
        }
    });

     // Smooth scroll to the top when the button is clicked
document.getElementById("floatingBackToTop").onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

</script>

<!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 MyPantryChef. All rights reserved.</p>
    </footer>
</body>
</html>


