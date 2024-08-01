<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Class MembershipAlreadyExistsException.
 */
class MembershipAlreadyExistsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Sei già membro di questa organizzazione');
    }
}
