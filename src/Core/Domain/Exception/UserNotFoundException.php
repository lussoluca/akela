<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

/**
 * Class UserNotFoundException.
 */
class UserNotFoundException extends \Exception
{
    /**
     * UserNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('L\'utente non è stato trovato');
    }
}
