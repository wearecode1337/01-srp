<?php

class CurrencyNumberFormatter {

    private $currency;

    function __construct($currency) {
        $this->currency = $currency;
    }

    public function format($number) {
        return number_format($number, 2) . " " . $this->currency;
    }
}

class SalaryCalculator {
    public function calculateSalary(Employee $employee) {
        return $employee->getHours() * $employee->getHourlyRate();
    }
}

class EmployeeRepository {

    public function save($employee)
    {
        file_put_contents("employees.db", serialize($employee));
    }

    public function load()
    {
        $employee = @unserialize(file_get_contents("employees.db"));
        return $employee;
    }

}

class Employee {

    private $name;
    private $hours;
    private $hourlyRate;

    function getName() {
      return $this->name;
    }

    function getHours() {
      return $this->hours;
    }

    function getHourlyRate() {
      return $this->hourlyRate;
    }

    function __construct($name, $hours, $hourlyRate){
        $this->name = $name;
        $this->hours = $hours;
        $this->hourlyRate = $hourlyRate;
    }

}

class EmployeesApplication {

  private $formatter;
  private $calculator;
  private $repository;

  function __construct(CurrencyNumberFormatter $formatter, SalaryCalculator $calculator, EmployeeRepository $repository)
  {
      $this->formatter = $formatter;
      $this->calculator = $calculator;
      $this->repository = $repository;
  }

  function displayEmployee(Employee $employee) {
    echo "========================\n";
    echo "Name: " . $employee->getName() . "\n";
    echo "Monthly hours: " . $employee->getHours() . "\n";
    echo "Hourly rate: " . $this->formatter->format($employee->getHourlyRate()) . "\n";
    echo $employee->getName()." earns ". $this->formatter->format($this->calculator->calculateSalary($employee)) . " per week\n";
    echo "========================\n";
  }

  function save(Employee $employee) {
    $this->repository->save($employee);
    echo "Employee " . $employee->getName() . " saved";
  }

  function loadEmployee() {
    $storedEmployee = $this->repository->load();
    if (!$storedEmployee) {
      echo "No employee found!\n";
    }
    return $storedEmployee;
  }


}

$application = new EmployeesApplication(new CurrencyNumberFormatter("USD"), new SalaryCalculator(), new EmployeeRepository());
$newRate = 15;
$storedEmployee = $application->loadEmployee();
if ($storedEmployee) {
  echo "Restored Employee: \n";
  $application->displayEmployee($storedEmployee);
  $newRate = $storedEmployee->getHourlyRate();
}
$employee = new Employee("Poet", 38.5, $newRate + 0.5);

echo "New Employee: \n";
$application->displayEmployee($employee);
$application->save($employee);
echo "\n";
