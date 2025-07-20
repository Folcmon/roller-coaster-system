<?php
namespace App\Libraries\Infrastructure\Repositories;

use App\Libraries\Domain\Entities\RollerCoaster;
use App\Libraries\Domain\Repositories\RollerCoasterRepositoryInterface;
use \Redis;

class RedisRollerCoasterRepository implements RollerCoasterRepositoryInterface
{
    private Redis $redis;
    private string $prefix;

    public function __construct(Redis $redis, string $prefix = 'coaster:')
    {
        $this->redis = $redis;
        $this->prefix = $prefix;
    }

    public function save(RollerCoaster $coaster): void
    {
        $this->redis->set($this->prefix . $coaster->id, serialize($coaster));
    }

    public function findById(string $id): ?RollerCoaster
    {
        $data = $this->redis->get($this->prefix . $id);
        return $data ? unserialize($data) : null;
    }

    public function delete(string $id): void
    {
        $this->redis->del($this->prefix . $id);
    }

    public function findAll(): array
    {
        $keys = $this->redis->keys($this->prefix . '*');
        $coasters = [];
        foreach ($keys as $key) {
            $data = $this->redis->get($key);
            if ($data) {
                $coasters[] = unserialize($data);
            }
        }
        return $coasters;
    }

    public function getPersonnel(): int
    {
        $value = $this->redis->get('personnel:count');
        return $value !== false ? (int)$value : 0;
    }

    public function setPersonnel(int $count): void
    {
        $this->redis->set('personnel:count', $count);
    }
} 