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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Employee Insertion</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>


    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="logo.jpg" class="h-11" alt="BAT Logo" />
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">BAT</span>
            </a>
            <div class="flex md:order-2">
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search" aria-expanded="false"
                    class="md:hidden text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none
                focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 me-1">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>

                <div class="relative hidden md:block">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm text-gray-900 border 
                border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 
                dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 
                dark:focus:border-green-500" placeholder="Search...">
                </div>

                <button data-collapse-toggle="navbar-search" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center 
            text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 
            focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-search"
                    aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
                <div class="relative mt-3 md:hidden">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="search-navbar" class="block w-full p-2 ps-10 text-sm 
                text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-green-500 focus:border-green-500 
                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 
                dark:focus:border-green-500" placeholder="Search...">
                </div>
                <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 
            rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white 
            dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="#empRegistration" class="block py-2 px-3 text-white bg-green-700 rounded 
                    md:bg-transparent md:text-green-700 md:p-0 md:dark:text-green-500"
                            aria-current="page">Employee Registration</a>
                    </li>
                    <li>
                        <a href="#empList" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 
                    md:hover:bg-transparent md:hover:text-green-700 md:p-0 md:dark:hover:text-green-500 
                    dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent 
                    dark:border-gray-700">Employee List</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent 
                    md:hover:text-green-700 md:p-0 dark:text-white md:dark:hover:text-green-500 dark:hover:bg-gray-700 
                    dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="wrapper">
        <!-- Form Section -->
        <section class="form-section">
            <h1 id="empRegistration" class="title">Employee Registration</h1>
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
            <h2 id="empList" class="title">Employee List</h2>
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

    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>