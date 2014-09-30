<?php

class NumberFormatter {
    public function format($wage) {
        return number_format($wage, 2) . " $";
    }
}

class WageCalculator {
    public $rate = 23;

    public function calculateWage(WorkingHours $hours) {
        return $hours->getWorkingHours() * $this->rate;
    }
}

class WorkingHours {
    private $hours = 0;

    public function getWorkingHours()
    {
        return $this->hours;
    }

    public function setWorkingHours($hours)
    {
        $this->hours = $hours;
        $this->save();
    }

    public function save()
    {
        file_put_contents("hours.db", serialize($this));
    }

    public static function load()
    {
        $hours = @unserialize(file_get_contents("hours.db"));
        if ($hours) return $hours;
        return new static;
    }
}

class Employee {

    public $name;
    private $formatter;
    private $calculator;
    private $hours;

    function __construct($name, NumberFormatter $formatter, WageCalculator $calculator, WorkingHours $hours)
    {
        $this->name = $name;
        $this->formatter = $formatter;
        $this->calculator = $calculator;
        $this->hours = $hours;
    }

    function calculatePay()
    {
        return $this->formatter->format(
            $this->calculator->calculateWage($this->hours)
        );
    }
}

$hours = WorkingHours::load();
$hours->setWorkingHours(12);
$employee = new Employee("Poet", new NumberFormatter(), new WageCalculator(), WorkingHours::load());

echo $employee->calculatePay();