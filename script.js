// JavaScript for MyPantryChef

// Event listener for the recipe card click
document.querySelectorAll('.recipe-card').forEach(card => {
    card.addEventListener('click', () => {
        const modal = document.getElementById('recipeModal');
        const modalOverlay = document.querySelector('.modal-overlay');
        modal.style.display = 'block';
        modalOverlay.style.display = 'block';
    });
});

// Event listener for closing the modal
document.getElementById('closeModal')?.addEventListener('click', () => {
    const modal = document.getElementById('recipeModal');
    const modalOverlay = document.querySelector('.modal-overlay');
    modal.style.display = 'none';
    modalOverlay.style.display = 'none';
});

// Show the loading spinner when the button is clicked
document.querySelector('button').addEventListener('click', () => {
    const spinner = document.querySelector('.spinner');
    spinner.style.display = 'block';

    // Simulate a loading delay
    setTimeout(() => {
        spinner.style.display = 'none';
        alert('Recipes based on your ingredients will be shown here!');
    }, 2000); // Adjust delay as needed
});

// Handle recipe search form submission
document.getElementById('searchForm')?.addEventListener('submit', function(e) {
    e.preventDefault(); 
    const ingredient = document.getElementById('ingredientInput').value;
    const dietaryPreference = document.getElementById('dietaryPreferences').value;
    const cookTime = document.getElementById('cookTime').value;
    
    const resultSection = document.getElementById('results');
    resultSection.innerHTML = `
        <p>Searching recipes with ${ingredient}, dietary preference: ${dietaryPreference}, and cooking time: ${cookTime} minutes</p>
        <ul>
            <li>Recipe 1 with ${ingredient}</li>
            <li>Recipe 2 with ${ingredient}</li>
        </ul>
    `;
});

// Handle recipe review submission
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const reviewText = document.getElementById('reviewInput').value;
    const reviewList = document.querySelector('.user-reviews');
    
    const newReview = document.createElement('li');
    newReview.textContent = `New User: "${reviewText}"`;
    reviewList.appendChild(newReview);
    
    document.getElementById('reviewInput').value = ''; // Clear the input field
});

// Handle saving recipes
document.getElementById('saveRecipeButton').addEventListener('click', function() {
    const recipeInput = document.getElementById('recipeInput');
    const recipeName = recipeInput.value.trim();

    if (recipeName) {
        const savedRecipesList = document.getElementById('savedRecipesList');
        const newRecipeItem = document.createElement('li');
        newRecipeItem.textContent = recipeName;
        savedRecipesList.appendChild(newRecipeItem);
        
        // Clear the input field
        recipeInput.value = '';
    } else {
        alert('Please enter a recipe name to save.');
    }
});

// Handle ingredient form submission
document.getElementById('ingredientForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get the ingredient input value
    const ingredientInput = document.getElementById('ingredientInputField');
    const ingredient = ingredientInput.value.trim();

    if (ingredient) {
        // Create a new list item for the ingredient
        const ingredientList = document.getElementById('ingredientList');
        const listItem = document.createElement('li');
        listItem.textContent = ingredient;
        
        // Append the new ingredient to the list
        ingredientList.appendChild(listItem);

        // Clear the input field after submission
        ingredientInput.value = '';
    } else {
        alert('Please enter a valid ingredient.');
    }
});

function updateCookingMethodInfo() {
    const methodSelect = document.getElementById('cookingMethod');
    const methodInfo = document.getElementById('methodInfo');
    const selectedMethod = methodSelect.value;

    let infoText = '';

    switch (selectedMethod) {
        case 'Baking':
            infoText = 'Baking involves cooking food in an oven using dry heat. Ideal for cakes, bread, and pastries.';
            break;
        case 'Grilling':
            infoText = 'Grilling cooks food over direct heat, giving it a smoky flavor. Perfect for meats and vegetables.';
            break;
        case 'Steaming':
            infoText = 'Steaming cooks food by surrounding it with steam, retaining nutrients and flavor.';
            break;
        case 'Frying':
            infoText = 'Frying involves cooking food in hot oil, resulting in a crispy texture. Great for quick meals!';
            break;
        case 'Slow Cooking':
            infoText = 'Slow cooking allows flavors to meld over time, producing tender, flavorful dishes.';
            break;
        case 'Pressure Cooking':
            infoText = 'Pressure cooking uses steam to cook food quickly under high pressure, retaining moisture.';
            break;
        default:
            infoText = '';
            break;
    }

    methodInfo.innerHTML = infoText;
}


