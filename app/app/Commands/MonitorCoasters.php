<?php
namespace App\Commands;

use \CodeIgniter\CLI\BaseCommand;
use \CodeIgniter\CLI\CLI;
use \React\EventLoop\Factory;
use Clue\React\Redis\Factory as RedisFactory;

class MonitorCoasters extends BaseCommand
{
    protected $group = 'Custom';
    protected $name = 'monitor:coasters';
    protected $description = 'Monitoruje statystyki kolejek górskich w czasie rzeczywistym.';

    public function run(array $params)
    {
        $loop = Factory::create();
        $prefix = env('app.env', 'dev') === 'production' ? 'prod_coaster:' : 'dev_coaster:';
        $redisFactory = new RedisFactory($loop);
        $redisFactory->createClient('redis:6379')->then(function ($redis) use ($loop, $prefix) {
            $loop->addPeriodicTimer(2, function () use ($redis, $prefix) {
                $redis->keys($prefix.'*')->then(function ($keys) use ($redis) {
                    foreach ($keys as $key) {
                        $redis->get($key)->then(function ($data) use ($key) {
                            $coaster = unserialize($data);
                            CLI::write("[{$coaster->id}] Personel: {$coaster->liczbaPersonelu}, Klienci: {$coaster->liczbaKlientow}");
                            // Tu można dodać logikę wykrywania problemów i logowania
                        });
                    }
                });
            });
        });
        $loop->run();
    }
} 