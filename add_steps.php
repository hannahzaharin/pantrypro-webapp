<?php
include("databaseconnection.php");

$recipeID = $_GET['recipeID']; // Retrieve the recipeID from the URL

// Fetch existing steps for this recipe
$existingSteps = [];
$query = "SELECT stepNumber, stepInstruction, SEC_TO_TIME(TIME_TO_SEC(stepDuration)) as stepDuration FROM tutorial_steps WHERE recipeID = '$recipeID'";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $existingSteps[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stepNumbers = $_POST['stepNumber'];
    $stepInstructions = $_POST['stepInstruction'];
    $stepHours = $_POST['stepHours'];
    $stepMinutes = $_POST['stepMinutes'];
    $stepSeconds = $_POST['stepSeconds'];

    // Loop through each set of step fields and insert them into the database
    for ($i = 0; $i < count($stepNumbers); $i++) {
        $stepNumber = mysqli_real_escape_string($connect, $stepNumbers[$i]);
        $stepInstruction = mysqli_real_escape_string($connect, $stepInstructions[$i]);

        // Format step duration as HH:MM:SS
        $stepDuration = sprintf("%02d:%02d:%02d", $stepHours[$i], $stepMinutes[$i], $stepSeconds[$i]);

        // Insert each step into tutorial_steps table if not already added
        $stepCheckQuery = "SELECT * FROM tutorial_steps WHERE recipeID = '$recipeID' AND stepNumber = '$stepNumber'";
        $stepCheckResult = mysqli_query($connect, $stepCheckQuery);
        
        if (mysqli_num_rows($stepCheckResult) === 0) {
            $query = "INSERT INTO tutorial_steps (recipeID, stepNumber, stepInstruction, stepDuration) VALUES ('$recipeID', '$stepNumber', '$stepInstruction', '$stepDuration')";
            mysqli_query($connect, $query);
        }
    }

    // Redirect to view the completed recipe
    header("Location: admin_dashboard.php?recipeID=$recipeID");
    exit();
}
?>

<!-- Form for adding tutorial steps -->
<form action="" method="POST" id="stepsForm">
    <div id="stepsContainer">
        <?php foreach ($existingSteps as $step): ?>
            <div class="step-row">
                <label>Step Number:</label>
                <input type="number" name="stepNumber[]" value="<?php echo htmlspecialchars($step['stepNumber']); ?>" required>

                <label>Step Instruction:</label>
                <textarea name="stepInstruction[]" required><?php echo htmlspecialchars($step['stepInstruction']); ?></textarea>

                <label>Step Duration:</label>
                <input type="number" name="stepHours[]" value="<?php echo explode(':', $step['stepDuration'])[0]; ?>" placeholder="HH" min="0" max="23" required> :
                <input type="number" name="stepMinutes[]" value="<?php echo explode(':', $step['stepDuration'])[1]; ?>" placeholder="MM" min="0" max="59" required> :
                <input type="number" name="stepSeconds[]" value="<?php echo explode(':', $step['stepDuration'])[2]; ?>" placeholder="SS" min="0" max="59" required>
            </div>
        <?php endforeach; ?>
        <div class="step-row">
            <label>Step Number:</label>
            <input type="number" name="stepNumber[]" required>

            <label>Step Instruction:</label>
            <textarea name="stepInstruction[]" required></textarea>

            <label>Step Duration:</label>
            <input type="number" name="stepHours[]" placeholder="HH" min="0" max="23" required> :
            <input type="number" name="stepMinutes[]" placeholder="MM" min="0" max="59" required> :
            <input type="number" name="stepSeconds[]" placeholder="SS" min="0" max="59" required>
        </div>
    </div>
    
    <button type="button" onclick="addStepRow()">Add Another Step</button>
    <input type="submit" value="Save Steps">
</form>

<script>
// JavaScript to add new step rows
function addStepRow() {
    const container = document.getElementById('stepsContainer');
    const row = document.createElement('div');
    row.className = 'step-row';
    row.innerHTML = `
        <label>Step Number:</label>
        <input type="number" name="stepNumber[]" required>
        
        <label>Step Instruction:</label>
        <textarea name="stepInstruction[]" required></textarea>

        <label>Step Duration:</label>
        <input type="number" name="stepHours[]" placeholder="HH" min="0" max="23" required> :
        <input type="number" name="stepMinutes[]" placeholder="MM" min="0" max="59" required> :
        <input type="number" name="stepSeconds[]" placeholder="SS" min="0" max="59" required>
    `;
    container.appendChild(row);
}
</script>
