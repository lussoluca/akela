<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

use Symfony\Component\Uid\Uuid;

/**
 * Class UserNotFoundException.
 */
class UserNotFoundException extends \Exception
{
    /**
     * UserNotFoundException constructor.
     */
    public function __construct(string|Uuid $userUuid)
    {
        if ($userUuid instanceof Uuid) {
            $userUuid = $userUuid->toString();
        }
        parent::__construct(sprintf('L\'utente %s non è stato trovato', $userUuid));
    }
}
