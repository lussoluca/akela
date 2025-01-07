<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

use Symfony\Component\Uid\Uuid;

class UnitNotFoundException extends \Exception
{
    public function __construct(string|Uuid $unitUuid)
    {
        if ($unitUuid instanceof Uuid) {
            $unitUuid = $unitUuid->toString();
        }
        parent::__construct(sprintf('L\'unità %s non è stata trovata', $unitUuid));
    }
}
