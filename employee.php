<?php

class employee
{
    public $id;
    public $empName;
    public $empAge;
    public $empAddress;
    public $empTax;
    public $empSalary;

    public function calculateSalary()
    {
        return $this->empSalary - ($this->empSalary * $this->empTax / 100);
    }
}
