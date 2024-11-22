<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

use Symfony\Component\Uid\Uuid;

class ProfileNotFountException extends \Exception
{
    /**
     * ProfileNotFountException constructor.
     */
    public function __construct(string|Uuid $scoutUuid)
    {
        if ($scoutUuid instanceof Uuid) {
            $scoutUuid = $scoutUuid->toString();
        }
        parent::__construct(sprintf('Il profilo %s non è stato trovato', $scoutUuid));
    }
}
