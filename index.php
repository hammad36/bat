<?php

require_once 'db.php';
require_once 'employee.php';

if (isset($_POST['submit'])) {

    $empName = filter_input(INPUT_POST, 'empName', FILTER_SANITIZE_STRING);
    $empAge = filter_input(INPUT_POST, 'empAge', FILTER_SANITIZE_NUMBER_INT);
    $empAddress = filter_input(INPUT_POST, 'empAddress', FILTER_SANITIZE_STRING);
    $empSalary = filter_input(INPUT_POST, 'empSalary', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $empTax = filter_input(INPUT_POST, 'empTax', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $employee = new employee($empName, $empAge, $empSalary, $empTax);
    $employee->empName = $empName;
    $employee->empAge = $empAge;
    $employee->empAddress = $empAddress;
    $employee->empSalary = $empSalary;
    $employee->empTax = $empTax;

    $sql = 'INSERT INTO employees (empName, empAge, empAddress, empSalary, empTax) VALUES ("' . $empName . '", ' . $empAge . ',
            "' . $empAddress . '", ' . $empSalary . ', ' . $empTax . ')';

    //inserting or updating in database
    if ($connection->exec($sql)) {
        $message = 'Employee ' . $empName . ' inserted successfully';
    } else {
        $error = true;
        $message = 'Error inserting ' . $empName;
    }
}
//reading from database back
$sql = 'SELECT * FROM employees';
$stmt = $connection->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_CLASS, 'employee');
$result = (is_array($result) && !empty($result)) ? $result : false;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Insertion</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <div class="wrapper">
        <!-- Form Section -->
        <section class="form-section">
            <h1>Register a New Employee</h1>
            <form class="appForm" method="post" enctype="application/x-www-form-urlencoded">
                <?php if (isset($message)) { ?>
                    <p class="message <?= isset($error) ? 'error' : '' ?>"><?= $message ?></p>
                <?php } ?>
                <div class="form-group">
                    <label for="empName">Employee Name:</label>
                    <input type="text" id="empName" name="empName" placeholder="Enter employee name">
                </div>
                <div class="form-group">
                    <label for="empAge">Employee Age:</label>
                    <input type="number" id="empAge" name="empAge" placeholder="Enter employee age" min="18" max="60" step="1">
                </div>
                <div class="form-group">
                    <label for="empAddress">Employee Address:</label>
                    <input type="text" id="empAddress" name="empAddress" placeholder="Enter employee address">
                </div>
                <div class="form-group">
                    <label for="empSalary">Employee Salary:</label>
                    <input type="number" id="empSalary" name="empSalary" placeholder="Enter employee salary" min="1500" max="9000" step="1">
                </div>
                <div class="form-group">
                    <label for="empTax">Tax Percentage (%):</label>
                    <input type="number" id="empTax" name="empTax" placeholder="Enter tax percentage" min="1" max="5" step=".01">
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Submit" class="btn">
                </div>
            </form>
        </section>


        <!-- Employee List Section -->
        <section class="employees">
            <h2>Employee List</h2>
            <table class="emp-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Salary</th>
                        <th>Tax (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (false !== $result) {
                        foreach ($result as $employee) {
                    ?>
                            <tr>
                                <td><?= $employee->empName ?></td>
                                <td><?= $employee->empAge ?></td>
                                <td><?= $employee->empAddress ?></td>
                                <td><?= round($employee->calculateSalary()) ?> L.E</td>
                                <td><?= $employee->empTax ?></td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <td colspan="5">Sorry no employees to list</td>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>