<?php declare(strict_types=1);

namespace integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class WagonApiTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testCreateWagon()
    {
        //given data for wagon creation
        $coasterId = $this->createCoaster($this->createCoasterTestData());
        //example wagaon json { ilosc_miejsc: 32, predkosc_wagonu: 1.2 }
        $data = [
            'coaster_id' => $coasterId,
            'ilosc_miejsc' => random_int(1, 100),
            'predkosc_wagonu' => random_int(1, 10) / 10 // speed in m/s
        ];

        //when we send a POST request to create a wagon
        $postUrl = "/api/coasters/{$coasterId}/wagons";
        $response = $this->withBody(json_encode($data))
            ->post($postUrl, [], ['Content-Type' => 'application/json']);
        $statusCode = $response->response()->getStatusCode();

        //then we expect a 201 Created response
        $this->assertSame(201, $statusCode);
        $this->assertJson($response->getJSON());
        $this->assertArrayHasKey('id', json_decode($response->getJSON(), true));
    }

    public function testDeleteWagon()
    {
        //given data for wagon creation
        $coasterId = $this->createCoaster($this->createCoasterTestData());
        $wagonId = $this->createWagon($coasterId, [
            'ilosc_miejsc' => random_int(1, 100),
            'predkosc_wagonu' => random_int(1, 10) / 10 // speed in m/s
        ]);

        //when we send a DELETE request to remove the wagon
        $deleteUrl = "/api/coasters/{$coasterId}/wagons/{$wagonId}";
        $response = $this->delete($deleteUrl);

        //then we expect a 200 OK response
        $this->assertSame(200, $response->response()->getStatusCode());
        $this->assertJson($response->getJSON());
        $this->assertArrayHasKey('status', json_decode($response->getJSON(), true));
    }

    private function createCoaster(array $data): string
    {
        $response = $this->withBody(json_encode($data))
            ->post('/api/coasters', [], ['Content-Type' => 'application/json']);
        $this->assertSame(201, $response->response()->getStatusCode());
        $json = json_decode($response->getJSON(), true);
        return $json['id'];
    }

    private function createCoasterTestData(): array
    {
        return [
            'liczba_personelu' => random_int(1, 100),
            'liczba_klientow' => random_int(1, 100),
            'dl_trasy' => random_int(100, 1000),
            'godziny_od' => '08:00',
            'godziny_do' => '16:00'
        ];
    }

    private function createWagon(string $coasterId, array $array)
    {
        $data = [
            'coaster_id' => $coasterId,
            'ilosc_miejsc' => $array['ilosc_miejsc'],
            'predkosc_wagonu' => $array['predkosc_wagonu']
        ];

        $postUrl = "/api/coasters/{$coasterId}/wagons";
        $response = $this->withBody(json_encode($data))
            ->post($postUrl, [], ['Content-Type' => 'application/json']);
        $this->assertSame(201, $response->response()->getStatusCode());
        $json = json_decode($response->getJSON(), true);
        return $json['id'];
    }

    protected function tearDown(): void
    {
        //connect to redis and remove all created coasters with related data to make all tests independent
        $redis = new \Redis();
        $redis->connect(env('redis.host', 'redis'), env('redis.port', 6379));
        $prefix = env('app.env', 'dev') === 'production' ? 'prod_coaster:' : 'dev_coaster:';
        $keys = $redis->keys("{$prefix}*");
        foreach ($keys as $key) {
            $redis->del($key);
        }
    }
}
