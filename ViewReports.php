<?php
session_start();

$db = mysqli_connect("studentdb-maria.gl.umbc.edu", "benayab1", "benayab1", "benayab1");

if (mysqli_connect_errno()) {
    exit("Error - could not connect to MySQL");
}

// Initialize variables
$expenses = [];
$sortByDate = $sortByCategory = "";

// Sorting by date and category
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sortByDate = $_POST['sortByDate'];
    $sortByCategory = $_POST['sortByCategory'];

    // Construct SQL query
    $sql = "SELECT * FROM expenses WHERE 1=1";
    if (!empty($sortByDate)) {
        $sql .= " AND DATE_FORMAT(date, '%m/%Y') = '$sortByDate'";
    }
    if ($sortByCategory != "All") {
        $sql .= " AND category = '$sortByCategory'";
    }
    
    $result = mysqli_query($db, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $expenses[] = $row;
        }
    }
}

mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Financial Reports - PennyPinchers</title>
    <link rel="stylesheet" href="styles.css" type="text/css"/>
</head>
<body>
    <header> <!-- Header Code Here --> </header>

    <div class="content-area">
        <h2>Financial Reports</h2>

        <form method="POST">
            <label for="sortByDate">Sort by Date (e.g., 10/2024):</label>
            <input type="text" name="sortByDate" placeholder="MM/YYYY"><br><br>

            <label for="sortByCategory">Sort by Category:</label>
            <select name="sortByCategory">
                <option>All</option>
                <option>Food</option>
                <option>Transportation</option>
            </select><br><br>

            <button type="submit">Filter</button>
        </form>

        <h3>Report Summary</h3>
        <table border="1">
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
            </tr>
            <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?php echo htmlspecialchars($expense['date']); ?></td>
                    <td><?php echo htmlspecialchars($expense['category']); ?></td>
                    <td><?php echo htmlspecialchars($expense['amount']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <button onclick="exportReport()">Export Report</button>
    </div>
</body>
<script>
function exportReport() {
    // Export functionality placeholder
    alert('Export functionality will be implemented here.');
}
</script>
</html>

