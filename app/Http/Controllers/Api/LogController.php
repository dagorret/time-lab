<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('time_test_logs');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sortField', 'id');
        $sortDirection = $request->get('sortOrder') == -1 ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDirection);

        $data = $query->paginate($request->get('rows', 15));

        return response()->json([
            'schema' => [
                ['field' => 'id', 'header' => 'ID', 'sortable' => true, 'width' => '10%'],
                ['field' => 'event_name', 'header' => 'Evento de Auditoría', 'sortable' => true, 'width' => '40%'],
                ['field' => 'status', 'header' => 'Estado', 'sortable' => true, 'width' => '20%'],
                ['field' => 'started_at', 'header' => 'Fecha Inicio', 'sortable' => true, 'width' => '30%'],
            ],
            'data' => $data->items(),
            'total' => $data->total(),
        ]);
    }
}
