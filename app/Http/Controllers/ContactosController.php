<?php

namespace App\Http\Controllers;

use App\Models\Contactos;
use Illuminate\Http\Request;

class ContactosController extends Controller
{
    public function index()
    {
        $contactos = Contactos::orderBy('id', 'DESC')->paginate(10);

        return view('contactos.index', compact('contactos'));
    }
}
