<?php declare(strict_types=1);

use App\Libraries\Domain\Repositories\RollerCoasterRepositoryInterface;
use App\Libraries\Domain\Repositories\WagonRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\Libraries\Application\CoasterService;
use App\Libraries\Domain\Entities\RollerCoaster;

class CoasterServiceTest extends TestCase
{
    public function testCalculateRequiredWagons()
    {
        $coaster = new RollerCoaster('test', 10, 250, 1000, '08:00', '16:00');
        $mockCoasterRepo = $this->createMock(RollerCoasterRepositoryInterface::class);
        $mockWagonRepo = $this->createMock(WagonRepositoryInterface::class);
        $service = new CoasterService($mockCoasterRepo, $mockWagonRepo);
        $this->assertEquals(3, $service->calculateRequiredWagons($coaster));
    }
} 