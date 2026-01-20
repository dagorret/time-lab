<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta
        $query = DB::table('time_test_logs');

        // 2. Filtro de Búsqueda (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // 3. Ordenamiento Dinámico (Sort)
        // sortOrder de Vue: 1 (ASC), -1 (DESC)
        $sortField = $request->get('sortField', 'id'); // id por defecto
        $sortDirection = $request->get('sortOrder') == -1 ? 'desc' : 'asc';
        
        $query->orderBy($sortField, $sortDirection);

        // 4. Paginación
        $rows = $request->get('rows', 15);
        $data = $query->paginate($rows);

        // 5. Respuesta con SCHEMA (La inteligencia de la UI)
        return response()->json([
        'schema' => [
            ['field' => 'id', 'header' => 'ID', 'sortable' => true, 'type' => 'text'],
            ['field' => 'nivel', 'header' => 'Nivel', 'sortable' => true, 'type' => 'badge'],
            ['field' => 'mensaje', 'header' => 'Evento', 'sortable' => false, 'type' => 'text'],
        ]
            ],
            'data' => $data->items(),
            'total' => $data->total(),
        ]);
    }
}
