<?php
namespace App\Libraries\Domain\Repositories;

use App\Libraries\Domain\Entities\Wagon;

interface WagonRepositoryInterface
{
    public function addWagon(string $coasterId, Wagon $wagon): void;
    public function removeWagon(string $coasterId, string $wagonId): void;
    public function findWagon(string $coasterId, string $wagonId): ?Wagon;
    /**
     * @return Wagon[]
     */
    public function findAllForCoaster(string $coasterId): array;
} 