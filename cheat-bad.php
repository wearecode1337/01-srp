<?php

class Employee {

    private $workingHours = 0;

    /**
     * @param int $hours
     */
    public function setHours($hours)
    {
        $this->workingHours = $hours;
    }

    public function calculatePay()
    {
        return number_format($this->workingHours * 23, 2) . " $";
    }

    public function save()
    {
        file_put_contents("employee.db", serialize($this));
    }

    public static function load()
    {
        return unserialize(file_get_contents("employee.db"));
    }

}

//$employee = Employee::load();

$employee = new Employee();
$employee->setHours(12);
$employee->save();

echo $employee->calculatePay();