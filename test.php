<?php
require_once('employee.php');
require_once('abstractModel.php');
require_once('db.php');

$emp = new employee('Ahmed', 23, 'Alex', 5000, 1.03);
$emp->create();
