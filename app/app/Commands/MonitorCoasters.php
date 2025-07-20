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
    protected $description = 'Monitoruje statystyki kolejek górskich w czasie rzeczywistym (pub/sub).';

    public function run(array $params)
    {
        $loop = Factory::create();
        $redisFactory = new RedisFactory($loop);
        $logFile = WRITEPATH . 'logs/monitoring.log';
        
        $redisFactory->createClient('redis:6379')->then(function ($redis) use ($logFile) {
            $redis->subscribe('monitoring');
            $redis->on('message', function ($channel, $message) use ($logFile) {
                $data = @json_decode($message, true);
                if (!$data) {
                    CLI::write("[monitoring] Otrzymano nieprawidłową wiadomość: $message", 'yellow');
                    return;
                }
                $output = "[{$data['timestamp']}] [{$data['coaster']}]\n";
                $output .= "1. Godziny działania: {$data['godziny_od']} - {$data['godziny_do']}\n";
                $output .= "2. Liczba wagonów: {$data['liczba_wagonow']}/{$data['liczba_wagonow_wymagana']}\n";
                $output .= "3. Dostępny personel: {$data['liczba_personelu']}/{$data['liczba_personelu_wymagana']}\n";
                $output .= "4. Klienci dziennie: {$data['liczba_klientow']}\n";
                if (isset($data['problem']) && $data['problem']) {
                    $output .= "5. Problem: {$data['problem']}\n";
                    // Logowanie do pliku
                    $logLine = "[{$data['timestamp']}] {$data['coaster']} - Problem: {$data['problem']}\n";
                    file_put_contents($logFile, $logLine, FILE_APPEND);
                    CLI::write($output, 'red');
                } else {
                    $output .= "5. Status: OK\n";
                    CLI::write($output, 'green');
                }
            });
            CLI::write("Nasłuchiwanie kanału monitoring...", 'cyan');
        });
        $loop->run();
    }
} 