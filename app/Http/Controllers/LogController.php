<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Time\Table\Facades\TiTable; // Asegúrate de que el paquete esté instalado
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Usamos la tabla 'logs' que llenamos con los 10,500 registros
        $query = DB::table('logs');
        
        return TiTable::query($query)->render();
    }
}
