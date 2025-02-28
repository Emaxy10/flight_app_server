<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use Illuminate\Support\Facades\Http;

class PassengerController extends Controller
{
    //

    public function pickFlightInfo(Request $request){
        //flight type, departure_airpot_id, arrival_airport_id, date    
        $flight_info = $request->flight_type;

        $flights= Flight::where('arrival_id',$request->arrival_id)
                            ->where('departure_id', $request->departure_id)
                            ->where('departure_date', $request->departure_date)->get();

        if(count($flights) < 1){
            return response()->json(['error' => 'Flight not avaliable'],400);
        }else{
            return $flights;
        }                            
 
    }

    public function bookFlight(Request $request){

    }
}
