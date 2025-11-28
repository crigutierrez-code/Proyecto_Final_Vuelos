<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = "flights";
    public $timestamps = true;
    
    protected $fillable = [
        'nave_id',
        'origin',
        'destination',
        'departure',
        'arrival',
        'price'
    ];

    // Relación con Nave
    public function nave()
    {
        return $this->belongsTo(Nave::class, 'nave_id');
    }

    // Relación con Reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'flight_id');
    }
}