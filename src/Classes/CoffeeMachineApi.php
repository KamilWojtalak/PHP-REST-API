<?php

declare(strict_types=1);

namespace App\Src\Classes;

class CoffeeMachineApi extends Api
{
    private $_config = [];

    public function __construct($config)
    {
        parent::__construct($config);
    }

    private function _getAllowedResources()
    {
        return $this->_config['URL_ALLOWED_RESOURCES'];
    }

    private function _urlToArray($url)
    {
        $chunks = explode($this->_config['URL_DELIMETER'], $url);

        /** Get rid of first empty array element */
        array_shift($chunks);

        return $chunks;
    }
}
