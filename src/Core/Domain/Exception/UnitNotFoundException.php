<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

class UnitNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('L\'unità non è stata trovata');
    }
}
