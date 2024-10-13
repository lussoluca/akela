<?php

namespace App\Core\Domain\Model;

interface GroupRepositoryInterface
{
    /**
     * @return \App\Core\Domain\Model\GroupInterface[]
     */
    public function all(): array;

    public function findOrFail(string $id, bool $allowDeleted = false): Group;

    public function find(string $id, bool $allowDeleted = false): ?Group;

    /**
     * @return \App\Core\Domain\Model\GroupInterface[]
     */
    public function findAll(bool $allowDeleted = false): array;

    public function add(GroupInterface $group): void;

    public function delete(GroupInterface $group): void;
}
