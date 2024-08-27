<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

/**
 * Class UserAlreadyExistsException.
 */
class OrganizationAlreadyExistsException extends \Exception
{
    /**
     * OrganizationAlreadyExistsException constructor.
     */
    public function __construct(string $type)
    {
        parent::__construct(sprintf('Esiste già un\'organizzazione con questa %s', $type));
    }
}
