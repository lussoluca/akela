<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Class InvalidRoleException.
 */
class InvalidRoleException extends \Exception
{
    /**
     * InvalidRoleException constructor.
     */
    public function __construct(string $role)
    {
        parent::__construct('Il ruolo '.$role.' non esiste');
    }
}
