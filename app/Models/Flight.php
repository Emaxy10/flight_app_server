<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_id',
        'arrival_id',
        'departure_code',
        'arrival_code',
        'departure_date',
        'arrival_date',
        'departure_time',
        'arrival_time',
        'economy',
        'business',
        'firstclass',
        'economy_price',
        'business_price',
        'firstclass_price',
        'code'
    ];
//
// Departure airport

    public function departureAirport(){
        return $this->belongsTo(Airport::class, 'departure_id');
    }

    //Arrival airport

    public function arrivalAirport(){
       return $this->belongsTo(Airport::class, 'arrival_id');
    }

    
}
