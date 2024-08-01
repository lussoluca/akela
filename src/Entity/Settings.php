<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;
use App\Entity\Core\Traits\TimestampableEntity;

#[Entity]
class Settings implements SettingsInterface
{
    use TimestampableEntity;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(type: 'boolean')]
    private bool $newsletter;

    #[Column(type: 'boolean')]
    private bool $publicProfile;

    #[Column(type: 'boolean')]
    private bool $messagesEnabled;

    #[Column(type: 'boolean')]
    private bool $platformNotifications;

    #[Column(type: 'boolean')]
    private bool $emailNotifications;

    public function __construct(
        bool $newsletter,
        bool $publicProfile,
        bool $platformNotifications,
        bool $emailNotifications,
        bool $messagesEnabled
    ) {
        $this->id = new UuidV4();
        $this->newsletter = $newsletter;
        $this->publicProfile = $publicProfile;
        $this->platformNotifications = $platformNotifications;
        $this->emailNotifications = $emailNotifications;
        $this->messagesEnabled = $messagesEnabled;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function hasNewsletter(): bool
    {
        return $this->newsletter;
    }

    public function hasPublicProfile(): bool
    {
        return $this->publicProfile;
    }

    public function hasPlatformNotifications(): bool
    {
        return $this->platformNotifications;
    }

    public function hasEmailNotifications(): bool
    {
        return $this->emailNotifications;
    }

    public function hasMessagesEnabled(): bool
    {
        return true; // $this->messagesEnabled;
    }

    public function update(
        bool $newsletter,
        bool $publicProfile,
        bool $platformNotifications,
        bool $emailNotifications,
        bool $messagesEnabled
    ): self {
        $this->newsletter = $newsletter;
        $this->publicProfile = $publicProfile;
        $this->platformNotifications = $platformNotifications;
        $this->emailNotifications = $emailNotifications;
        $this->messagesEnabled = $messagesEnabled;

        return $this;
    }
}
