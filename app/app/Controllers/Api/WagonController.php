<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Domain\Entities\Wagon;
use App\Libraries\Application\CoasterService;

class WagonController extends BaseController
{
    private CoasterService $service;

    public function __construct()
    {
        $this->service = service('coasterService');
    }

    public function create($coasterId)
    {
        $data = $this->request->getJSON(true);
        if (!isset($data['ilosc_miejsc'], $data['predkosc_wagonu'])) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON(['error' => 'Brak wymaganych danych']);
        }
        $wagon = new Wagon(
            uniqid('wagon_'),
            (int)$data['ilosc_miejsc'],
            (float)$data['predkosc_wagonu']
        );
        $this->service->addWagon($coasterId, $wagon);
        return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON(['id' => $wagon->id]);
    }

    public function delete($coasterId, $wagonId)
    {
        $this->service->removeWagon($coasterId, $wagonId);
        return $this->response->setStatusCode(ResponseInterface::HTTP_OK)->setJSON(['status' => 'usunięto']);
    }
} 