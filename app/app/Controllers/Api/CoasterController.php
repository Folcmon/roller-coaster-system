<?php declare(strict_types=1);
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Domain\Entities\RollerCoaster;
use App\Libraries\Application\CoasterService;

class CoasterController extends BaseController
{
    private CoasterService $service;

    public function __construct()
    {
        $this->service = service('coasterService');
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        // Walidacja danych (przykładowa, szczegółowa będzie osobno)
        if (!isset($data['liczba_personelu'], $data['liczba_klientow'], $data['dl_trasy'], $data['godziny_od'], $data['godziny_do'])) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON(['error' => 'Brak wymaganych danych']);
        }
        $coaster = new RollerCoaster(
            uniqid('coaster_'),
            (int)$data['liczba_personelu'],
            (int)$data['liczba_klientow'],
            (int)$data['dl_trasy'],
            $data['godziny_od'],
            $data['godziny_do']
        );
        $this->service->addCoaster($coaster);
        return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON(['id' => $coaster->id]);
    }

    public function update($coasterId)
    {
        $data = $this->request->getJSON(true);
        $coaster = $this->service->getCoaster($coasterId);
        if (!$coaster) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Nie znaleziono kolejki']);
        }
        // Aktualizujemy tylko wybrane pola
        if (isset($data['liczba_personelu'])) $coaster->liczbaPersonelu = (int)$data['liczba_personelu'];
        if (isset($data['liczba_klientow'])) $coaster->liczbaKlientow = (int)$data['liczba_klientow'];
        if (isset($data['godziny_od'])) $coaster->godzinyOd = $data['godziny_od'];
        if (isset($data['godziny_do'])) $coaster->godzinyDo = $data['godziny_do'];
        $this->service->updateCoaster($coaster);
        return $this->response->setStatusCode(ResponseInterface::HTTP_OK)->setJSON(['status' => 'zaktualizowano']);
    }
} 