<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'location',
        'code'
    ];

    // Flights departing from this airport
    public function departures(){
        return $this->hasMany(Flight::class, 'departure_id');
    }
    
    //Flights arriving to this airport
    public function arrivals(){
       return $this->hasMany(Flight::class, 'arrival_id');
    }
}
