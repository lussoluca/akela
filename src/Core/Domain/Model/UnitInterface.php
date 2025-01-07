<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Enum\UnitType;

interface UnitInterface
{
    public function getGroup(): ?GroupInterface;

    public function update(string $name, UnitType $type): self;
}
