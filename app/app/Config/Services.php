<?php

namespace Config;

use App\Libraries\Application\CoasterService;
use App\Libraries\Infrastructure\Repositories\RedisRollerCoasterRepository;
use App\Libraries\Infrastructure\Repositories\RedisWagonRepository;
use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function rollerCoasterRepository($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('rollerCoasterRepository');
        }
        $redis = new \Redis();
        $redis->connect(env('redis.host', 'redis'), env('redis.port', 6379));
        $prefix = env('app.env', 'dev') === 'production' ? 'prod_coaster:' : 'dev_coaster:';
        return new RedisRollerCoasterRepository($redis, $prefix);
    }

    public static function wagonRepository($getShared = true): RedisWagonRepository
    {
        if ($getShared) {
            return static::getSharedInstance('wagonRepository');
        }
        $redis = new \Redis();
        $redis->connect(env('redis.host', 'redis'), env('redis.port', 6379));
        $prefix = env('app.env', 'dev') === 'production' ? 'prod_coaster:' : 'dev_coaster:';
        return new RedisWagonRepository($redis, $prefix);
    }

    public static function coasterService($getShared = true): CoasterService
    {
        if ($getShared) {
            return static::getSharedInstance('coasterService');
        }
        return new CoasterService(
            static::rollerCoasterRepository(),
            static::wagonRepository()
        );
    }
}
