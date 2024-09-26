<?php

class employee
{
    private $id;
    private $empName;
    private $empAge;
    private $empAddress;
    private $empTax;
    private $empSalary;

    public function __construct($empName, $empAge, $empAddress, $empTax, $empSalary)
    {
        $this->empName = $empName;
        $this->empAge = $empAge;
        $this->empAddress = $empAddress;
        $this->empTax = $empTax;
        $this->empSalary = $empSalary;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function calculateSalary()
    {
        return $this->empSalary - ($this->empSalary * $this->empTax / 100);
    }
}
