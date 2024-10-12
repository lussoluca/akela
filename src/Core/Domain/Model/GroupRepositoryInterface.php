<?php

namespace App\Core\Domain\Model;

interface GroupRepositoryInterface {

  public function all(): array;

  public function findOrFail(string $id, bool $allowDeleted = false): Group;

  public function find(string $id, bool $allowDeleted = false): ?Group;

  public function findAll(bool $allowDeleted = false): array;

  public function add(GroupInterface $group): void;

  public function delete(GroupInterface $group): void;

}