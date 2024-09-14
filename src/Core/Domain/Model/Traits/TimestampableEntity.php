<?php

namespace App\Core\Domain\Model\Traits;

use Doctrine\ORM\Mapping\Column;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableEntity
{
    #[Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    protected \DateTime $createdAt;

    #[Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'update')]
    protected \DateTime $updatedAt;

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
