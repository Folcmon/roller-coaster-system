<?php
// Model domenowy: Wagon
namespace App\Libraries\Domain\Entities;

class Wagon
{
    public string $id;
    public int $iloscMiejsc;
    public float $predkoscWagonu;
    public ?string $czasOstatniegoZjazdu;
    public bool $dostepny;

    public function __construct(
        string $id,
        int $iloscMiejsc,
        float $predkoscWagonu,
        ?string $czasOstatniegoZjazdu = null,
        bool $dostepny = true
    ) {
        $this->id = $id;
        $this->iloscMiejsc = $iloscMiejsc;
        $this->predkoscWagonu = $predkoscWagonu;
        $this->czasOstatniegoZjazdu = $czasOstatniegoZjazdu;
        $this->dostepny = $dostepny;
    }
} 