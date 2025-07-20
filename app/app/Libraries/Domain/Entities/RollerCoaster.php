<?php
// Model domenowy: Kolejka górska
namespace App\Libraries\Domain\Entities;

class RollerCoaster
{
    public string $id;
    public int $liczbaPersonelu;
    public int $liczbaKlientow;
    public int $dlugoscTrasy;
    public string $godzinyOd;
    public string $godzinyDo;
    /** @var Wagon[] */
    public array $wagony = [];

    public function __construct(
        string $id,
        int $liczbaPersonelu,
        int $liczbaKlientow,
        int $dlugoscTrasy,
        string $godzinyOd,
        string $godzinyDo,
        array $wagony = []
    ) {
        $this->id = $id;
        $this->liczbaPersonelu = $liczbaPersonelu;
        $this->liczbaKlientow = $liczbaKlientow;
        $this->dlugoscTrasy = $dlugoscTrasy;
        $this->godzinyOd = $godzinyOd;
        $this->godzinyDo = $godzinyDo;
        $this->wagony = $wagony;
    }
} 