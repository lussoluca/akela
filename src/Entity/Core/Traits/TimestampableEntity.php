<?php

namespace App\Entity\Core\Traits;

use Doctrine\ORM\Mapping\Column;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableEntity
{
    /**
     * @Gedmo\Timestampable(on="create")
     */
    #[Column(type: 'datetime')]
    protected \DateTime $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     */
    #[Column(type: 'datetime')]
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
