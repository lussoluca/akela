<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

/**
 * Class OrganizationNotFoundException.
 */
class OrganizationNotFoundException extends \Exception
{
    /**
     * OrganizationNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('L\'organizzazione non è stato trovata');
    }
}
