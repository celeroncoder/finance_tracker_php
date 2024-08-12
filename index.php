<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Modify your SQL queries to include user_id
function addTransaction($type, $amount, $category, $description, $date) {
	global $pdo;
	$user_id = $_SESSION['user_id'];
	$sql = "INSERT INTO transactions (user_id, type, amount, category, description, date) VALUES (?, ?, ?, ?, ?, ?)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user_id, $type, $amount, $category, $description, $date]);
}

function getTransactions() {
	global $pdo;
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user_id]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMonthlySummary() {
	global $pdo;
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT 
							YEAR(date) as year,
							MONTH(date) as month,
							SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
							SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
					FROM transactions
					WHERE user_id = ?
					GROUP BY YEAR(date), MONTH(date)
					ORDER BY YEAR(date) DESC, MONTH(date) DESC
					LIMIT 12";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user_id]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    
    addTransaction($type, $amount, $category, $description, $date);
    header('Location: index.php');
    exit();
}

$transactions = getTransactions();
$monthlySummary = getMonthlySummary();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Personal Finance Tracker</title>
	<style>/* General Styles */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 100%;
    max-width: 700px;
    margin: auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1, h2 {
    color: #333;
    text-align: center;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
}

form input, form select {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

form button {
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #0056b3;
}

/* Login and Register specific styles */
.auth-form {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.auth-form h2 {
    margin-bottom: 20px;
    color: #333;
}

.auth-form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.auth-form button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.auth-form button:hover {
    background-color: #0056b3;
}

.auth-form p {
    text-align: center;
    margin-top: 15px;
}

.auth-form a {
    color: #007bff;
    text-decoration: none;
}

.auth-form a:hover {
    text-decoration: underline;
}

.error {
    color: #dc3545;
    text-align: center;
    margin-bottom: 15px;
}

/* Main application styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.dashboard {
    margin-top: 30px;
}

#monthlyChart {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
}
</style>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
	<div class="container">
		<h1>Personal Finance Tracker</h1>
		<p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">Logout</a></p>

		<form action="index.php" method="post">
			<select name="type" required>
				<option value="income">Income</option>
				<option value="expense">Expense</option>
			</select>
			<input type="number" name="amount" step="0.01" required placeholder="Amount">
			<input type="text" name="category" required placeholder="Category">
			<input type="text" name="description" placeholder="Description">
			<input type="date" name="date" required>
			<button type="submit">Add Transaction</button>
		</form>

		<div class="dashboard">
			<h2>Monthly Summary</h2>
			<canvas id="monthlyChart"></canvas>
		</div>

		<h2>Recent Transactions</h2>
		<table>
			<thead>
				<tr>
					<th>Date</th>
					<th>Type</th>
					<th>Amount</th>
					<th>Category</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($transactions as $transaction): ?>
				<tr>
					<td>
						<?= htmlspecialchars($transaction['date']) ?>
					</td>
					<td>
						<?= htmlspecialchars($transaction['type']) ?>
					</td>
					<td>
						<?= htmlspecialchars($transaction['amount']) ?>
					</td>
					<td>
						<?= htmlspecialchars($transaction['category']) ?>
					</td>
					<td>
						<?= htmlspecialchars($transaction['description']) ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
		const ctx = document.getElementById('monthlyChart').getContext('2d');
		
		// PHP to pass data to JavaScript
		const monthlySummary = <?php echo json_encode($monthlySummary); ?>;
		
		const labels = monthlySummary.map(item => `${item.year}-${item.month}`);
		const incomeData = monthlySummary.map(item => item.total_income);
		const expenseData = monthlySummary.map(item => item.total_expense);
		
		new Chart(ctx, {
				type: 'bar',
				data: {
						labels: labels,
						datasets: [
								{
										label: 'Income',
										data: incomeData,
										backgroundColor: 'rgba(75, 192, 192, 0.6)',
										borderColor: 'rgba(75, 192, 192, 1)',
										borderWidth: 1
								},
								{
										label: 'Expense',
										data: expenseData,
										backgroundColor: 'rgba(255, 99, 132, 0.6)',
										borderColor: 'rgba(255, 99, 132, 1)',
										borderWidth: 1
								}
						]
				},
				options: {
						responsive: true,
						scales: {
								y: {
										beginAtZero: true
								}
						},
						plugins: {
								title: {
										display: true,
										text: 'Monthly Income and Expenses'
								}
						}
				}
		});
	});
	</script>
</body>

</html>