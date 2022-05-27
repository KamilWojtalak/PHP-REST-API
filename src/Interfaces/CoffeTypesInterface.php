<?php

namespace App\Src\Interfaces;

interface CoffeTypesInterface
{
    const COFFEE_ESPRESSO = 'espresso';
    const COFFEE_AMERICANO = 'americano';
    const COFFEE_WITH_MILK = 'coffeeWithMilk';
    const COFFEE_WITH_FROTHED_MILK = 'coffeeWithFrothedMilk';

    const IMPLEMENTED_COFFEE_TYPES = [
        self::COFFEE_ESPRESSO,
        self::COFFEE_AMERICANO,
        self::COFFEE_WITH_MILK,
        self::COFFEE_WITH_FROTHED_MILK,
    ];
}
