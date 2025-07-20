<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class RollercoasterController extends Controller
{
    public function index()
    {
        return view('rollercoasters/index');
    }

    public function wagons($coasterId)
    {
        return view('wagons/index');
    }

    public function personnel()
    {
        return view('rollercoasters/personnel');
    }
} 