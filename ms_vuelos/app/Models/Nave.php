<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nave extends Model
{
    protected $table = "naves";
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'capacity',
        'model'
    ];

    // Relación con Vuelos
    public function flights()
    {
        return $this->hasMany(Flight::class, 'nave_id');
    }
}