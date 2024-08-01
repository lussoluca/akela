<?php

namespace App\Entity\Core\Traits;

use Doctrine\ORM\Mapping\Column;

trait SoftDeleteableEntity
{
    #[Column(type: 'datetime', nullable: true)]
    protected ?\DateTime $deletedAt;

    /**
     * Set or clear the deleted at timestamp.
     */
    public function setDeletedAt(\DateTime $deletedAt = null): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get the deleted at timestamp value. Will return null if
     * the entity has not been soft deleted.
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * Check if the entity has been soft deleted.
     */
    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
