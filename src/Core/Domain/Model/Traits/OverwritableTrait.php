<?php

namespace App\Core\Domain\Model\Traits;

trait OverwritableTrait
{
    protected bool $overwrite = false;

    public function isOverwritable(): bool
    {
        return $this->overwrite;
    }

    public function setOverwrite(bool $overwrite): void
    {
        $this->overwrite = $overwrite;
    }
}
