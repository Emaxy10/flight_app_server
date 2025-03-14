<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        "flight_id",
        "email",
        "passenger",
        "contact",
        "class",
        "price"

    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class, "flight_id");
    }
}
