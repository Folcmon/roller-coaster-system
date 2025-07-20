<?php
namespace App\Libraries\Infrastructure\Repositories;

use App\Libraries\Domain\Entities\Wagon;
use App\Libraries\Domain\Repositories\WagonRepositoryInterface;
use \Redis;

class RedisWagonRepository implements WagonRepositoryInterface
{
    private Redis $redis;
    private string $prefix;

    public function __construct(Redis $redis, string $prefix = 'coaster:')
    {
        $this->redis = $redis;
        $this->prefix = $prefix;
    }

    public function addWagon(string $coasterId, Wagon $wagon): void
    {
        $key = $this->prefix . $coasterId . ':wagons';
        $this->redis->hSet($key, $wagon->id, serialize($wagon));
    }

    public function removeWagon(string $coasterId, string $wagonId): void
    {
        $key = $this->prefix . $coasterId . ':wagons';
        $this->redis->hDel($key, $wagonId);
    }

    public function findWagon(string $coasterId, string $wagonId): ?Wagon
    {
        $key = $this->prefix . $coasterId . ':wagons';
        $data = $this->redis->hGet($key, $wagonId);
        return $data ? unserialize($data) : null;
    }

    public function findAllForCoaster(string $coasterId): array
    {
        $key = $this->prefix . $coasterId . ':wagons';
        $all = $this->redis->hGetAll($key);
        $result = [];
        foreach ($all as $data) {
            $result[] = unserialize($data);
        }
        return $result;
    }
} 