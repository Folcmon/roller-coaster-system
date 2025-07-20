<?php declare(strict_types=1);

namespace integration;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class CoasterApiTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testCreateCoaster()
    {
        //given data for coaster creation
        $data = [
            'liczba_personelu' => 5,
            'liczba_klientow' => 100,
            'dl_trasy' => 500,
            'godziny_od' => '08:00',
            'godziny_do' => '16:00'
        ];

        //when we send a POST request to create a coaster
        $response = $this->withBody(json_encode($data))
            ->post('/api/coasters', [], ['Content-Type' => 'application/json']);
        $statusCode = $response->response()->getStatusCode();

        //then we expect a 201 Created response
        $this->assertSame(201, $statusCode);
        $this->assertJson($response->getJSON());
        $this->assertArrayHasKey('id', json_decode($response->getJSON(), true));
    }

    public function testUpdateCoaster()
    {
        //given data for coaster creation
        $coasterId = $this->createCoaster($this->createCoasterTestData());
        $data = [
            'liczba_personelu' => 10,
            'liczba_klientow' => 200,
            'dl_trasy' => 1000,
            'godziny_od' => '09:00',
            'godziny_do' => '17:00'
        ];

        //when we send a PUT request to update the coaster
        $response = $this->withBody(json_encode($data))
            ->put("/api/coasters/{$coasterId}", [], ['Content-Type' => 'application/json']);
        $statusCode = $response->response()->getStatusCode();

        //then we expect a 200 OK response
        $this->assertSame(200, $statusCode);
        $this->assertJson($response->getJSON());
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

    private function createCoaster(array $data): string
    {
        $response = $this->withBody(json_encode($data))
            ->post('/api/coasters', [], ['Content-Type' => 'application/json']);
        $this->assertSame(201, $response->response()->getStatusCode());
        $json = json_decode($response->getJSON(), true);
        return $json['id'];
    }

    protected function setUp(): void
    {
        parent::setUp();
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