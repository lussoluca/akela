<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use App\Exception\InvalidRoleException;

/**
 * Class Role.
 */
#[Embeddable]
class Role implements \Stringable
{
    public const ROLE_ORGANIZATION_ADMIN = 'ROLE_ORGANIZATION_ADMIN';
    public const ROLE_ORGANIZATION_MEMBER = 'ROLE_ORGANIZATION_MEMBER';
    public const ROLE_ORGANIZATION_EDITOR = 'ROLE_ORGANIZATION_EDITOR';

    /** @var string[] */
    private array $allowedRoles = [
        self::ROLE_ORGANIZATION_ADMIN,
        self::ROLE_ORGANIZATION_MEMBER,
        self::ROLE_ORGANIZATION_EDITOR,
    ];

    /** @var string[] */
    private array $printable = [
        self::ROLE_ORGANIZATION_ADMIN => 'Amministratore',
        self::ROLE_ORGANIZATION_MEMBER => 'Membro',
        self::ROLE_ORGANIZATION_EDITOR => 'Editor',
    ];

    #[Column(name: 'name', type: 'string', length: 32, nullable: false)]
    private string $name;

    /**
     * Role constructor.
     *
     * @throws InvalidRoleException
     */
    final private function __construct(string $name)
    {
        if (!in_array($name, $this->allowedRoles)) {
            throw new InvalidRoleException($name);
        }

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->printable[$this->getName()];
    }

    public static function createOrganizationAdminRole(): self
    {
        return new static(self::ROLE_ORGANIZATION_ADMIN);
    }

    public static function createOrganizationEditorRole(): self
    {
        return new static(self::ROLE_ORGANIZATION_EDITOR);
    }

    public static function createOrganizationMemberRole(): self
    {
        return new static(self::ROLE_ORGANIZATION_MEMBER);
    }

    /**
     * @throws \Exception
     */
    public static function fromString(string $name): self
    {
        return match ($name) {
            self::ROLE_ORGANIZATION_ADMIN => self::createOrganizationAdminRole(),
            self::ROLE_ORGANIZATION_EDITOR => self::createOrganizationEditorRole(),
            self::ROLE_ORGANIZATION_MEMBER => self::createOrganizationMemberRole(),
            default => throw new \Exception(),
        };
    }

    public function getName(): string
    {
        return $this->name;
    }
}
