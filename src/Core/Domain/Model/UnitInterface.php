<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

interface UnitInterface
{
    public function getGroup(): ?GroupInterface;
}
