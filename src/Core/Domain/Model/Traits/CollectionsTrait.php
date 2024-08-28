<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\Traits;

use Doctrine\Common\Collections\Collection;

/**
 * Trait CollectionsTrait.
 */
trait CollectionsTrait
{
    /**
     * @phpstan-ignore-next-line
     */
    public function collectionEquals(Collection $a, Collection $b): bool
    {
        if ($a->count() != $b->count()) {
            return false;
        }

        $equals = true;
        foreach ($a->toArray() as $item) {
            if (!$b->contains($item)) {
                $equals = false;
            }
        }

        return $equals;
    }
}
