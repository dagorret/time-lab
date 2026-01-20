<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Esta es la versión "Vanilla" que no falla
        $data = DB::table('time_test_logs')
            ->paginate($request->get('rows', 15));

        return response()->json([
            'data' => $data->items(),
            'total' => $data->total(),
        ]);
    }
}
