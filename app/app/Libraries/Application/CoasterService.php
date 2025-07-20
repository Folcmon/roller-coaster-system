<?php
namespace App\Libraries\Application;

use App\Libraries\Domain\Entities\RollerCoaster;
use App\Libraries\Domain\Entities\Wagon;
use App\Libraries\Domain\Repositories\RollerCoasterRepositoryInterface;
use App\Libraries\Domain\Repositories\WagonRepositoryInterface;

class CoasterService
{
    private RollerCoasterRepositoryInterface $coasterRepo;
    private WagonRepositoryInterface $wagonRepo;

    public function __construct(RollerCoasterRepositoryInterface $coasterRepo, WagonRepositoryInterface $wagonRepo)
    {
        $this->coasterRepo = $coasterRepo;
        $this->wagonRepo = $wagonRepo;
    }

    public function addCoaster(RollerCoaster $coaster): void
    {
        $this->coasterRepo->save($coaster);
    }

    public function updateCoaster(RollerCoaster $coaster): void
    {
        $this->coasterRepo->save($coaster);
    }

    public function addWagon(string $coasterId, Wagon $wagon): void
    {
        $this->wagonRepo->addWagon($coasterId, $wagon);
    }

    public function removeWagon(string $coasterId, string $wagonId): void
    {
        $this->wagonRepo->removeWagon($coasterId, $wagonId);
    }

    public function getCoaster(string $coasterId): ?RollerCoaster
    {
        return $this->coasterRepo->findById($coasterId);
    }

    public function getAllCoasters(): array
    {
        return $this->coasterRepo->findAll();
    }

    public function getWagonsForCoaster(string $coasterId): array
    {
        return $this->wagonRepo->findAllForCoaster($coasterId);
    }

    public function getPersonnel(): int
    {
        return $this->coasterRepo->getPersonnel();
    }

    public function setPersonnel(int $count): void
    {
        $this->coasterRepo->setPersonnel($count);
    }

    public function getSystemStatus(): array
    {
        $coasters = $this->getAllCoasters();
        $status = [];
        foreach ($coasters as $coaster) {
            $stat = $this->calculateStaffAndWagonStatus($coaster);
            if ($stat['staffDiff'] < 0) {
                $status[] = [
                    'coaster' => $coaster->id,
                    'type' => 'brak',
                    'message' => 'Brakuje ' . abs($stat['staffDiff']) . ' pracowników'
                ];
            } elseif ($stat['staffDiff'] > 0) {
                $status[] = [
                    'coaster' => $coaster->id,
                    'type' => 'nadmiar',
                    'message' => 'Nadmiar ' . $stat['staffDiff'] . ' pracowników'
                ];
            }
            if ($stat['wagonDiff'] < 0) {
                $status[] = [
                    'coaster' => $coaster->id,
                    'type' => 'brak',
                    'message' => 'Brakuje ' . abs($stat['wagonDiff']) . ' wagonów'
                ];
            } elseif ($stat['wagonDiff'] > 0) {
                $status[] = [
                    'coaster' => $coaster->id,
                    'type' => 'nadmiar',
                    'message' => 'Nadmiar ' . $stat['wagonDiff'] . ' wagonów'
                ];
            }
        }
        return $status;
    }

    public function getCoasterStatus(string $coasterId): array
    {
        $coaster = $this->getCoaster($coasterId);
        if (!$coaster) return [];
        $stat = $this->calculateStaffAndWagonStatus($coaster);
        $status = [];
        if ($stat['staffDiff'] < 0) {
            $status[] = [
                'coaster' => $coaster->id,
                'type' => 'brak',
                'message' => 'Brakuje ' . abs($stat['staffDiff']) . ' pracowników'
            ];
        } elseif ($stat['staffDiff'] > 0) {
            $status[] = [
                'coaster' => $coaster->id,
                'type' => 'nadmiar',
                'message' => 'Nadmiar ' . $stat['staffDiff'] . ' pracowników'
            ];
        }
        if ($stat['wagonDiff'] < 0) {
            $status[] = [
                'coaster' => $coaster->id,
                'type' => 'brak',
                'message' => 'Brakuje ' . abs($stat['wagonDiff']) . ' wagonów'
            ];
        } elseif ($stat['wagonDiff'] > 0) {
            $status[] = [
                'coaster' => $coaster->id,
                'type' => 'nadmiar',
                'message' => 'Nadmiar ' . $stat['wagonDiff'] . ' wagonów'
            ];
        }
        return $status;
    }

    // Logika wyliczania braków personelu i wagonów
    public function calculateStaffAndWagonStatus(RollerCoaster $coaster): array
    {
        $wagons = $this->wagonRepo->findAllForCoaster($coaster->id);
        $requiredStaff = 1 + count($wagons) * 2;
        $staffDiff = $coaster->liczbaPersonelu - $requiredStaff;
        return [
            'requiredStaff' => $requiredStaff,
            'staffDiff' => $staffDiff,
            'requiredWagons' => $this->calculateRequiredWagons($coaster),
            'wagonDiff' => count($wagons) - $this->calculateRequiredWagons($coaster),
        ];
    }

    // Przykładowa logika wyliczania wymaganej liczby wagonów
    public function calculateRequiredWagons(RollerCoaster $coaster): int
    {
        // Załóżmy, że jeden wagon obsłuży X klientów dziennie (np. 100)
        $WAGON_CAPACITY = 100;
        return (int)ceil($coaster->liczbaKlientow / $WAGON_CAPACITY);
    }
} 