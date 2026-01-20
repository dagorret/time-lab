<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Importamos el trait que encontraste (ajusta el namespace si es necesario)
use Time\Laravel\InteractsWithTiTable; 

class TimeLog extends Model
{
    use InteractsWithTiTable;

    protected $table = 'time_test_logs'; // La tabla que creó tu comando
}
