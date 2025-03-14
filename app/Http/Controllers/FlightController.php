<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Requests\UpdateFlightRequest;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Initialize an array to store flight data
        $flightData = [];

        // 
        $flights = Flight::all();
        foreach ($flights as $flight){

           $departureAirport = $flight->departureAirport;
           $arrivalAirport = $flight->arrivalAirport;

           //Add flight data to the array

           $flightData[] = [
            'id' => $flight->id,
            'code' => $flight->code,
            'departure_date' => $flight->departure_date,
            'departure_time' => $flight->departure_time,
            'arrival_date' => $flight->arrival_date,
            'arrival_time' => $flight->arrival_time,

            'departure' => $departureAirport ? [
                'id' => $departureAirport->id,
                'name' => $departureAirport->name,
                'code' => $departureAirport->code,
            ]: null,

            'arrival' => $arrivalAirport ? [
                'id' => $arrivalAirport->id,
                'name' => $arrivalAirport->name,
                'code' => $arrivalAirport->code,
            ] : null,
           ];   
          
        }

        return response()->json($flightData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function availableFlights(Request $request)
    {
        $departure = $request->input('departure');
        $arrival = $request->input('arrival');
        $departure_date  = $request->input('departure_date');


        // $flights = Flight::where('departure_id', $departure)
        //             ->where('arrival_id', $arrival)->get();

        $flights = Flight::with(['departureAirport', 'arrivalAirport'])
                            ->where('departure_id', $departure)
                            ->where('departure_date', $departure_date)
                            ->where('arrival_id', $arrival)->get();
            
            return response()->json($flights);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFlightRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFlightRequest $request)
    {
        function genFlightCode(){
            $rand_number = rand(1000, 10000);
            $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $rand_str = '';
            $charactersLength = strlen($chars);
            $len = 2;
    
            for ($i = 0; $i < $len; $i++) {
        
                if (!preg_match('/^[0-9]+$/', $chars)) {
                    $rand_str.= $chars[random_int(0, $charactersLength - 1)];
                } 
            }
            $flight_code = $rand_str."-". $rand_number;
            
            $row = Flight::where("code", $flight_code)->get();
    
            if(count($row) > 0){
                return genFlightCode();
            }else{
                return $flight_code;
            }
        }
        
        //check if flight alreday exists
        
        $results = Flight::where('departure_id',$request->departure_id)
                    ->where('arrival_id', $request->arrival_id)
                    ->where('departure_date', $request->departure_date)
                    ->where('departure_time', $request->departure_time)->get();

        //if the flight already exist throw error
        if(count($results) > 0){
            return response()->json(['error' => 'Flight has already been created'],400);
        }else{
           $economy_price = $request->flight_price;
           $business_price = $request->flight_price * 3;
           $firstclass_price = $request->flight_price * 5;
           $flight_code = genFlightCode();

            $flight = Flight::create([
                'departure_id' => $request->departure,
                'arrival_id' => $request->arrival,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time,
                'arrival_date' => $request->arrival_date,
                'arrival_time' => $request->arrival_time,
                'economy' => $request->economy_seat,
                'business' => $request->business_seat,
                'firstclass' => $request->firstclass_seat,
                'economy_price' => $economy_price,
                'business_price' => $business_price,
                'firstclass_price' => $firstclass_price,
                'code' => $flight_code,
            ]);

            return $flight;
    
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Flight  $flight
     * @return \Illuminate\Http\Response
     */
    public function show(Flight $flight)
    {
        //
        $flightData = [];
        $departureAirport = $flight->departureAirport;
        $arrivalAirport = $flight->arrivalAirport;

        $flightData = [
            'id' => $flight->id,
            'code' => $flight->code,
            'departure_date' => $flight->departure_date,
            'departure_time' => $flight->departure_time,
            'arrival_date' => $flight->arrival_date,
            'arrival_time' => $flight->arrival_time,
            'firstclass_seat' => $flight->firstclass,
            'economy_seat'=> $flight->economy,
            'business_seat'=> $flight->business,
            'flight_price' => $flight->economy_price,

            'departure' => $departureAirport ? [
                'id' => $departureAirport->id,
                'name' => $departureAirport->name,
                'code' => $departureAirport->code,
                'location' => $departureAirport->location,
            ]: null,

            'arrival' => $arrivalAirport ? [
                'id' => $arrivalAirport->id,
                'name' => $arrivalAirport->name,
                'code' => $arrivalAirport->code,
                'location'=> $arrivalAirport->location
            ] : null,
           ];

           return response()->json($flightData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flight  $flight
     * @return \Illuminate\Http\Response
     */
    public function edit(Flight $flight)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFlightRequest  $request
     * @param  \App\Models\Flight  $flight
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        //
            $economy_price = $request->flight_price;
            $business_price = $request->flight_price * 3;
            $firstclass_price = $request->flight_price * 5;

        $flight->update([
            'departure_id' => $request->departure,
            'arrival_id' => $request->arrival,
            'departure_date' => $request->departure_date,
            'departure_time' => $request->departure_time,
            'arrival_date' => $request->arrival_date,
            'arrival_time' => $request->arrival_time,
            'economy' => $request->economy_seat,
            'business' => $request->business_seat,
            'firstclass' => $request->firstclass_seat,
            'economy_price' => $economy_price,
            'business_price' => $business_price,
            'firstclass_price' => $firstclass_price,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Flight  $flight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flight $flight)
    {
        //
        $flight->delete();
    }

   
}
