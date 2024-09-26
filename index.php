<?php
require_once 'db.php';
require_once 'employee.php';

$result = false;

if (isset($_POST['submit'])) {
    $empName = filter_input(INPUT_POST, 'empName', FILTER_SANITIZE_STRING);
    $empAge = filter_input(INPUT_POST, 'empAge', FILTER_SANITIZE_NUMBER_INT);
    $empAddress = filter_input(INPUT_POST, 'empAddress', FILTER_SANITIZE_STRING);
    $empSalary = filter_input(INPUT_POST, 'empSalary', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $empTax = filter_input(INPUT_POST, 'empTax', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $params = [
        ':empName'   => $empName,
        ':empAge'    => $empAge,
        ':empAddress' => $empAddress,
        ':empSalary' => $empSalary,
        ':empTax'    => $empTax
    ];

    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql = 'UPDATE employees SET empName = :empName, empAge = :empAge, empAddress = :empAddress, empSalary = :empSalary, 
        empTax = :empTax WHERE id = :id';
        $params[':id'] = $id;
    } else {
        $sql = 'INSERT INTO employees (empName, empAge, empAddress, empSalary, empTax) 
        VALUES (:empName, :empAge, :empAddress, :empSalary, :empTax)';
    }
    $stmt = $connection->prepare($sql);

    if ($stmt->execute($params) === true) {
        header("Location: index.php?add=Employee saved successfully");
        exit();
    } else {
        header("Location: index.php?error=Error saving employee");
        exit();
    }
}

//UPDATE
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($id > 0) {
        $sql = 'SELECT * FROM employees WHERE id = :id';
        $result = $connection->prepare($sql);
        $foundUser = $result->execute(array(':id' => $id));
        if ($foundUser === true) {
            $user = $result->fetchAll(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                'employee',
                array('empName', 'empAge', 'empAddress', 'empSalary', 'empTax')
            );
            $user = array_shift($user);
        }
    }
}

//DELETE
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($id > 0) {
        $sql = 'DELETE FROM employees WHERE id = :id';
        $result = $connection->prepare($sql);
        $foundUser = $result->execute(array(':id' => $id));

        if ($foundUser) {
            header("Location: index.php?delete=Employee successfully deleted");
            exit();
        } else {
            header("Location: index.php?error=Failed to delete employee");
            exit();
        }
    }
}


// Retrieve employees from database
$sql = 'SELECT * FROM employees';
$stmt = $connection->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'employee', array('empName', 'empAge', 'empAddress', 'empSalary', 'empTax'));
$result = (is_array($result) && !empty($result)) ? $result : false;

$pdo = null; // Close the connection
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>BAT</title>
    <link rel="stylesheet" href="main.css">
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #ffffff; border-bottom: 1px solid #ddd;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="Blogo.jpg" alt="BAT Logo" class="me-2" style="height: 44px;">
                <span class="navbar-brand mb-0 h1" style="color: rgb(17, 24, 39);">BAT</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#empRegistration" style="color: #28a745; font-weight: 500;">Employee Registration</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#empList" style="color: #333;">Employee List</a>
                    </li>
                </ul>

                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search" style="border-radius: 6px; border: 1px solid #ced4da;">
                </form>
            </div>
        </div>
    </nav>



    <div class="wrapper">
        <!-- Form Section -->
        <section class="form-section">
            <?php
            include_once "alertHandler.php";

            $alertHandler = new alertHandler();
            $alertHandler->handleAlert();
            ?>

            <h1 id="empRegistration" class="title">Employee Registration</h1>
            <form class="appForm" method="post" enctype="application/x-www-form-urlencoded">
                <div id="alert-placeholder"></div>

                <div class="form-group">
                    <label for="empName">Employee Name:</label>
                    <input type="text" id="empName" name="empName" placeholder="Enter employee name"
                        value="<?= isset($user) ? $user->empName : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="empAge">Employee Age:</label>
                    <input type="number" id="empAge" name="empAge" placeholder="Enter employee age" min="18" max="60" step="1"
                        value="<?= isset($user) ? $user->empAge : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="empAddress">Employee Address:</label>
                    <input type="text" id="empAddress" name="empAddress" placeholder="Enter employee address"
                        value="<?= isset($user) ? $user->empAddress : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="empSalary">Employee Salary:</label>
                    <input type="number" id="empSalary" name="empSalary" placeholder="Enter employee salary" min="1500" max="9000" step="1"
                        value="<?= isset($user) ? $user->empSalary : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="empTax">Tax Percentage (%):</label>
                    <input type="number" id="empTax" name="empTax" placeholder="Enter tax percentage" min="1" max="5" step=".01"
                        value="<?= isset($user) ? $user->empTax : ''; ?>" required>
                </div>
                <div class="form-group-submit">
                    <input type="submit" name="submit" value="Submit" class="btn">
                </div>
            </form>
        </section>


        <!-- Employee List Section -->
        <section class="employees">
            <h2 id="empList" class="title">Employee Directory</h2>
            <table class="emp-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Salary</th>
                        <th>Tax (%)</th>
                        <th>Actions</th>
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
                                <td>
                                    <a href="../BAT/?action=edit&id=<?= $employee->id ?>" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="../BAT/?action=delete&id=<?= $employee->id ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this employee?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>

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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>