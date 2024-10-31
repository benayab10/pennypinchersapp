<?php
// Connect to the database
$db = mysqli_connect("studentdb-maria.gl.umbc.edu", "benayab1", "benayab1", "benayab1");

// Check the database connection
if (mysqli_connect_errno()) {
    exit("Error - could not connect to MySQL");
}

// Variables
$expenseAmount = $expenseCategory = $expenseDate = "";
$errors = [];

// Validate form data 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expenseAmount = $_POST['expenseAmount'];
    $expenseCategory = $_POST['expenseCategory'];
    $expenseDate = $_POST['expenseDate'];
    $isRecurring = isset($_POST['recurringExpense']) ? 1 : 0;

    // Validation using regex
    if (!preg_match("/^\d+(\.\d{2})?$/", $expenseAmount)) {
        $errors[] = "Please enter a valid amount (e.g., 10.50).";
    }
    if (!preg_match("/^(0[1-9]|1[0-2])\/([0-2][0-9]|3[0-1])\/\d{4}$/", $expenseDate)) {
        $errors[] = "Please enter a valid date (MM/DD/YYYY).";
    }

    // If no errors, proceed to insert into the database
    if (empty($errors)) {
        $query = "INSERT INTO expenses (amount, category, date, is_recurring) VALUES ('$expenseAmount', '$expenseCategory', '$expenseDate', '$isRecurring')";
        
        if (mysqli_query($db, $query)) {
            echo "Expense added successfully!";
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Expenses - PennyPinchers</title>
    <link rel="stylesheet" href="styles.css" type="text/css"/>
</head>
<body>
    <header> <!-- Header Code Here --> </header>

    <div class="content-area">
        <h2>Add Expenses</h2>
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST">
            <label for="expenseAmount">Amount:</label>
            <input type="number" name="expenseAmount" required><br><br>

            <label for="expenseCategory">Category:</label>
            <select name="expenseCategory">
                <option>Food</option>
                <option>Transportation</option>
                <option>Entertainment</option>
            </select><br><br>

            <label for="expenseDate">Date (e.g., 10/10/2024):</label>
            <input type="text" name="expenseDate" required><br><br>

            <input type="checkbox" name="recurringExpense">
            <label for="recurringExpense">Set as recurring expense</label><br><br>

            <button type="submit">Add Expense</button>
        </form>
    </div>
</body>
</html>
