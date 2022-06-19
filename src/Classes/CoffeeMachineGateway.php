<?php

declare(strict_types=1);

namespace App\Src\Classes;

use App\Src\Interfaces\CoffeTypesInterface;

class CoffeeMachineGateway extends Gateway implements CoffeTypesInterface
{
    /** Is coffee machine is turned on or off */
    public $status;

    /** db connection instance */
    protected $dbc;

    /** Content */
    protected $_coffeeStatus;
    protected $_milkStatus;
    protected $_waterStatus;
    protected $_coffeePower;
    protected $_coffeeDouble = 1;

    /** table names */
    private $_db_main_table = 'coffe_machines';
    private $_db_content_table = 'content';
    private $_db_specification_table = 'specification';

    const COFFE_MACHINE_ID = 1;

    public function __construct($dbc)
    {
        parent::__construct($dbc);
    }

    public function validateStatusData($input)
    {
        /** get turn param */
        $value = $input['turn'];

        /** define allowed values */
        $allowedValues = ['on', 'off'];

        /** check if there is allowed value provided by 'turn' param */
        return in_array($value, $allowedValues);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusMessage($status)
    {
        /** return proper message */
        switch ($this->status) {
            case '0':
                return 'Coffee machine is turned off. Turn it on to make a coffee.';
                break;
            case '1':
                return 'Coffee machine is turned on.';
                break;
        }
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function changeStatus($data)
    {
        $userTurnValue = $data['turn'];

        $statusEquivalents = [
            'off' => '0',
            'on' => '1',
        ];

        $userTurnValueTransformed = $statusEquivalents[$userTurnValue];

        $outputMessages = [
            '0' => 'Coffee machine is still turned off.',
            '1' => 'Coffee machine is still turned on.',
        ];

        /** If current status value matches the given one output that nothing chaned */
        if ($this->status === $userTurnValueTransformed) {
            return $outputMessages[$this->status];
        }

        /** If there is a difference output that you turned it on or off */
        if ($userTurnValueTransformed === '0') {
            $this->_setStatusDb('0');
            return 'Coffee machine has been turned off';
        }
        if ($userTurnValueTransformed === '1') {
            $this->_setStatusDb('1');
            return 'Coffee machine has been turned on';
        }
    }

    public function getStatusDb()
    {
        $sql = "SELECT turned FROM {$this->_db_main_table} WHERE id=" . static::COFFE_MACHINE_ID;

        $stmt = $this->_dbc->query($sql);

        /** assign to status property value of turned column */
        $this->status = (string) $stmt->fetchObject()->turned;
    }

    public function getSpecification()
    {
        $sql = "SELECT m.*, s.* FROM {$this->_db_main_table} m INNER JOIN {$this->_db_specification_table} s ON m.id=s.coffee_machine_id WHERE m.id=" . static::COFFE_MACHINE_ID;

        $stmt = $this->_dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll()[0];

        $formatOutputData = [
            'coffeMachineName' => $data['name'],
            'ACPower' => $data['ac_power'],
            'manufacturer' => $data['manufacturer'],
            'waterBoiler' => $data['water_boiler'],
            'steamBoiler' => $data['steam_boiler'],
            'maxConsumption' => $data['max_consumption'],
            '***secret***' => 'It can go to the store and buy coffee when the coffee runs out. Pretty cool feature!1!'
        ];

        return $formatOutputData;
    }

    public function getContentDb()
    {
        $sql = "SELECT c.* FROM {$this->_db_main_table} m INNER JOIN {$this->_db_content_table} c ON m.id=c.coffee_machine_id WHERE m.id=" . static::COFFE_MACHINE_ID;

        $stmt = $this->_dbc->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll()[0];

        $this->_coffeeStatus = $data['coffee_status'];
        $this->_milkStatus = $data['milk_status'];
        $this->_waterStatus = $data['water_status'];
        $this->_coffeePower = ($data['coffee_power'] / 10) + 1; // If in db you have 5 then it is converted to 1.5. Min-max = 1.0-2.0
    }

    public function getContent()
    {
        return [
            'coffeeStatus' => $this->_coffeeStatus,
            'milkStatus' => $this->_milkStatus,
            'waterStatus' => $this->_waterStatus,
            'coffeePower' => $this->_coffeePower,
        ];
    }

    public function validateContentUpdateData($input)
    {
        $inputKeys = array_keys($input);
        $inputValues = array_values($input);

        $allowedKeys = $this->_allowedContentUpdateKeys();

        /** Check whether provided keys are the keys we want */
        foreach ($inputKeys as $key) {
            (!in_array($key, $allowedKeys)) && CustomFunctions::outputJson('You have used wrong key to update coffee machine\'s content');
        }
        /** Check if user provided only non negative numbers */
        foreach ($inputValues as $value) {
            (!filter_var($value, FILTER_VALIDATE_INT) || $value < 0) && CustomFunctions::outputJson('Value can only be an non negative integer');
        }
    }

    public function updateContent($input)
    {
        $this->_updateGatewayContent($input);

        $this->_checkIfIngredientsAreInTheirScope();

        $this->_updateContentDb();

        return 'Coffe machine content has been uploaded.';
    }

    public function getCoffee()
    {
        /** to zamienń na consty z CoffeTypesInterface */
        return [
            self::COFFEE_ESPRESSO,
            self::COFFEE_AMERICANO,
            self::COFFEE_WITH_MILK,
            self::COFFEE_WITH_FROTHED_MILK,
        ];
    }

    public function validateCoffeeData($input)
    {
        /** Check whether there is a type param */
        (!isset($input['type'])) && CustomFunctions::displayError('`type` param is required to make a coffee.');

        /** Check if there is a valid coffee type */
        (!in_array($input['type'], $this->getCoffee())) && CustomFunctions::displayError('Invalid type of a coffee');
    }

    public function makeCoffee($input)
    {
        $type = $input['type'] ?? '';
        $this->_coffeeDouble = ($input['double'] === "true") ? 2 : 1;
        switch ($type) {
            case self::COFFEE_ESPRESSO:
                $ingredients = $this->_requiredIngredients(self::COFFEE_ESPRESSO);
                $this->_checkIfYouCanMakeACoffee($ingredients);
                return $this->_makeCoffee(self::COFFEE_ESPRESSO, $ingredients);
                break;
            case self::COFFEE_AMERICANO:
                $ingredients = $this->_requiredIngredients(self::COFFEE_AMERICANO);
                $this->_checkIfYouCanMakeACoffee($ingredients);
                return $this->_makeCoffee(self::COFFEE_AMERICANO, $ingredients);
                break;
            case self::COFFEE_WITH_MILK:
                $ingredients = $this->_requiredIngredients(self::COFFEE_WITH_MILK);
                $this->_checkIfYouCanMakeACoffee($ingredients);
                return $this->_makeCoffee(self::COFFEE_WITH_MILK, $ingredients);
                break;
            case self::COFFEE_WITH_FROTHED_MILK:
                $ingredients = $this->_requiredIngredients(self::COFFEE_WITH_FROTHED_MILK);
                $this->_checkIfYouCanMakeACoffee($ingredients);
                return $this->_makeCoffee(self::COFFEE_WITH_FROTHED_MILK, $ingredients);
                break;
        }
    }

    public function validatePowerParam($input)
    {
        if (!isset($input['power'])) CustomFunctions::displayError('`power` param is required there');

        ($this->_isPowerNotInRange($input['power'])) && CustomFunctions::displayError('`power` param must be value between 0 - 10');
    }

    public function _isPowerNotInRange($value)
    {
        return !($value >= 0 && $value <= 10);
    }

    public function updatePower($input)
    {
        $this->_updatePowerDb($input['power']);

        $this->_coffeePower = $input['power'] / 10 + 1;

        return 'You have set coffee power to ' . $input['power'];
    }

    protected function _setStatusDb($status)
    {
        $sql = "UPDATE {$this->_db_main_table} SET turned=:status WHERE id=" . static::COFFE_MACHINE_ID;

        $stmt = $this->_dbc->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }

    private function _allowedContentUpdateKeys()
    {
        return [
            'coffeeStatus',
            'milkStatus',
            'waterStatus',
        ];
    }

    private function _updateGatewayContent($input)
    {
        isset($input['coffeeStatus']) && $this->_coffeeStatus += $input['coffeeStatus'];
        isset($input['milkStatus']) && $this->_milkStatus += $input['milkStatus'];
        isset($input['waterStatus']) && $this->_waterStatus += $input['waterStatus'];
    }

    private function _checkIfIngredientsAreInTheirScope()
    {
        $this->_checkIfIngredientsAreNotOverloaded();
        $this->_checkIfIngredientsAreNotNegativeValues();
    }

    private function _checkIfIngredientsAreNotOverloaded()
    {
        ($this->_coffeeStatus > 100) && $this->_coffeeStatus = 100;
        ($this->_milkStatus > 100) && $this->_milkStatus = 100;
        ($this->_waterStatus > 100) && $this->_waterStatus = 100;
    }

    private function _checkIfIngredientsAreNotNegativeValues()
    {
        ($this->_coffeeStatus < 0) && $this->_coffeeStatus = 0;
        ($this->_milkStatus < 0) && $this->_milkStatus = 0;
        ($this->_waterStatus < 0) && $this->_waterStatus = 0;
    }

    private function _updateContentDb()
    {
        $sql = "UPDATE {$this->_db_content_table} c INNER JOIN {$this->_db_main_table} m ON c.coffee_machine_id=m.id SET coffee_status=:coffeStatus, milk_status=:milkStatus, water_status=:waterStatus WHERE m.id=" . static::COFFE_MACHINE_ID;

        $stmt = $this->_dbc->prepare($sql);
        $stmt->bindParam(':coffeStatus', $this->_coffeeStatus);
        $stmt->bindParam(':milkStatus', $this->_milkStatus);
        $stmt->bindParam(':waterStatus', $this->_waterStatus);
        $stmt->execute();
    }

    private function _requiredIngredients($type)
    {
        $coffeeProportions = $this->_coffeePower * $this->_coffeeDouble;
        switch ($type) {
            case self::COFFEE_ESPRESSO:
                return [
                    'coffee' => 9 * $coffeeProportions,
                    'water' => 20
                ];
                break;
            case self::COFFEE_AMERICANO:
                return [
                    'coffee' => 7 * $coffeeProportions,
                    'water' => 40
                ];
                break;
            case self::COFFEE_WITH_MILK:
                return [
                    'coffee' => 7 * $coffeeProportions,
                    'water' => 30,
                    'milk' => 10,
                ];
                break;
            case self::COFFEE_WITH_FROTHED_MILK:
                return [
                    'coffee' => 7 * $coffeeProportions,
                    'water' => 30,
                    'milk' => 15,
                ];
                break;
        }
    }

    private function _checkIfYouCanMakeACoffee($ingredients)
    {
        /** Check espresso */
        (isset($ingredients['coffee'])) && ($ingredients['coffee'] * $this->_coffeePower > $this->_coffeeStatus) && CustomFunctions::displayError('There is not enough coffee');
        /** Check water */
        (isset($ingredients['water'])) && ($ingredients['water'] > $this->_waterStatus) && CustomFunctions::displayError('There is not enough water');
        /** Check milk */
        (isset($ingredients['milk'])) && ($ingredients['milk'] > $this->_milkStatus) && CustomFunctions::displayError('There is not enough milk');
    }

    private function _makeCoffee($name, $ingredients)
    {
        $this->_boilTheWater();
        $this->_mixAllIngredients();
        $this->_reduceIngredientsGateway($ingredients);
        $this->_updateContentDb($ingredients);

        return "Your $name coffee has been created";
    }

    private function _boilTheWater()
    {
        // Bul bul
    }

    private function _mixAllIngredients()
    {
        // mix mix
    }

    private function _reduceIngredientsGateway($ingredients)
    {
        /** Subract coffee, water and milk */
        isset($ingredients['coffee']) && $this->_coffeeStatus -= $ingredients['coffee'];
        isset($ingredients['water']) && $this->_waterStatus -= $ingredients['water'];
        isset($ingredients['milk']) && $this->_milkStatus -= $ingredients['milk'];
    }

    private function _updatePowerDb($power)
    {
        $sql = "UPDATE {$this->_db_content_table} c INNER JOIN {$this->_db_main_table} m ON c.coffee_machine_id=m.id SET coffee_power=:coffePower WHERE m.id=" . static::COFFE_MACHINE_ID;

        $stmt = $this->_dbc->prepare($sql);
        $stmt->bindParam(':coffePower', $power);
        $stmt->execute();
    }
}
