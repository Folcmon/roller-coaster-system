<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $coaster = [
        'liczba_personelu' => 'required|is_natural_no_zero',
        'liczba_klientow'  => 'required|is_natural_no_zero',
        'dl_trasy'         => 'required|is_natural_no_zero',
        'godziny_od'       => 'required|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]',
        'godziny_do'       => 'required|regex_match[/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/]',
    ];

    public $wagon = [
        'ilosc_miejsc'     => 'required|is_natural_no_zero',
        'predkosc_wagonu'  => 'required|decimal',
    ];
}
