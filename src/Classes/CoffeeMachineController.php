<?php

declare(strict_types=1);

namespace App\Src\Classes;

class CoffeeMachineController extends Controller
{
    protected $_gateway;

    public function __construct(
        $gateway
    ) {
        parent::__construct($gateway);
    }

    public function processRequest(string $resource, $urlAfterResource, string $method = 'GET'): void
    {
        /** Get coffee machine status from the db */
        $this->_gateway->getStatusDb();

        switch ($resource) {
            case 'status':
                switch ($method) {
                    case 'GET':
                        $status = $this->_gateway->getStatus();
                        $feedbackMessage = $this->_gateway->getStatusMessage($status);

                        CustomFunctions::outputJson($feedbackMessage);
                        break;
                    case 'PUT':
                        $input = CustomFunctions::getHttpInput();

                        if (!$this->_gateway->validateStatusData($input)) CustomFunctions::displayError('Allowed `turn` parameter values are in the docs. This one is not allowed.');

                        $feedbackMessage = $this->_gateway->changeStatus($input);

                        CustomFunctions::outputJson($feedbackMessage);
                        break;
                    default:
                        CustomFunctions::displayError('This method is not implemented to work with this resource.', 405, ['GET', 'PUT']);
                        break;
                }
                break;
        }

        /** Check if coffe machine is turned on */
        if ($this->_gateway->status) {
            switch ($resource) {
                case 'specification':
                    switch ($method) {
                        case 'GET':
                            $feedbackMessage = $this->_gateway->getSpecification();

                            CustomFunctions::outputJson($feedbackMessage);
                        default:
                            CustomFunctions::displayError('This method is not implemented to work with this resource.', 405, ['GET']);
                            break;
                    }
                    break;
                case 'content':
                    $this->_gateway->getContentDb();
                    if ($method === 'GET') {
                        $feedbackMessage = $this->_gateway->getContent();
                        CustomFunctions::outputJson($feedbackMessage);
                    } elseif ($method === 'PUT') {
                        /** get php input data */
                        $input = CustomFunctions::getHttpInput();

                        /** validate keys and values */
                        $this->_gateway->validateContentUpdateData($input);

                        /** update gateway and db */
                        $feedbackMessage = $this->_gateway->updateContent($input);

                        CustomFunctions::outputJson($feedbackMessage);
                    } else {
                        CustomFunctions::displayError('This method is not implemented to work with this resource.', 405, ['GET', 'PUT']);
                    }
                    break;
                case 'coffee':
                    $this->_gateway->getContentDb();

                    if ($method === 'GET') {
                        $feedbackMessage = $this->_gateway->getCoffee();
                        CustomFunctions::outputJson($feedbackMessage);
                    } elseif ($method === 'POST') {
                        /** get php input data */
                        $input = CustomFunctions::getHttpInput();

                        /** validate keys and values */
                        $this->_gateway->validateCoffeeData($input);

                        /** update gateway and db */
                        $feedbackMessage = $this->_gateway->makeCoffee($input);

                        CustomFunctions::outputJson($feedbackMessage);
                    } elseif ($method === 'PUT') {
                        $input = CustomFunctions::getHttpInput();

                        if (isset($urlAfterResource[0]) && $urlAfterResource[0] === 'power') {
                            $this->_gateway->validatePowerParam($input);
                            $feedbackMessage = $this->_gateway->updatePower($input);

                            CustomFunctions::outputJson($feedbackMessage);
                        }
                    } else {
                        CustomFunctions::displayError('This method is not implemented to work with this resource.', 405, ['GET', 'POST', 'PUT']);
                    }
                    break;
            }
        } else {
            CustomFunctions::displayError('First try to turn on the coffee machine.');
        }
    }
}
