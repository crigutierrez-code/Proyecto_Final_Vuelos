<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = "reservations";
    public $timestamps = true;
    
    protected $fillable = [
        'user_id',
        'flight_id',
        'status',
        'reserved_at'
    ];

    // Relación con Usuario (necesitamos acceder a la tabla users)
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // Relación con Vuelo
    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }
}