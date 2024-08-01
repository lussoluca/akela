<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\UuidV4;

interface SettingsInterface
{
    public function getId(): UuidV4;

    public function hasNewsletter(): bool;

    public function hasPublicProfile(): bool;

    public function hasPlatformNotifications(): bool;

    public function hasEmailNotifications(): bool;

    public function hasMessagesEnabled(): bool;

    public function update(
        bool $newsletter,
        bool $publicProfile,
        bool $platformNotifications,
        bool $emailNotifications,
        bool $messagesEnabled
    ): self;
}
