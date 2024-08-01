<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Class MembershipNotFoundException.
 */
class MembershipNotFoundException extends \Exception
{
    /**
     * OrganizationNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('La membership non è stato trovata');
    }
}
