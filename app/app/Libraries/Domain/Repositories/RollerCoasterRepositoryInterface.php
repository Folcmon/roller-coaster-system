<?php
namespace App\Libraries\Domain\Repositories;

use App\Libraries\Domain\Entities\RollerCoaster;

interface RollerCoasterRepositoryInterface
{
    public function save(RollerCoaster $coaster): void;
    public function findById(string $id): ?RollerCoaster;
    public function delete(string $id): void;
    /**
     * @return RollerCoaster[]
     */
    public function findAll(): array;
} 