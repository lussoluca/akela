<?php

declare(strict_types=1);

namespace App\Core\Domain\Exception;

use App\Core\Domain\Model\UserInterface;

/**
 * Class UserAlreadyExistsException.
 */
class UserAlreadyExistsException extends \Exception
{
    private UserInterface $user;

    /**
     * UserAlreadyExistsException constructor.
     */
    public function __construct(string $message, UserInterface $user)
    {
        parent::__construct($message);

        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
