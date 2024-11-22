<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

use Symfony\Component\Uid\Uuid;

class ScoutNotFoundException extends \Exception
{
    public function __construct(string|Uuid $scoutUuid)
    {
        if ($scoutUuid instanceof Uuid) {
            $scoutUuid = $scoutUuid->toString();
        }
        parent::__construct(sprintf('Lo scout %s non è stato trovato', $scoutUuid));
    }
}
