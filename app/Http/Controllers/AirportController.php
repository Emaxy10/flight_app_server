<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Http\Requests\StoreAirportRequest;
use App\Http\Requests\UpdateAirportRequest;

class AirportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $airports = Airport::all();
        return $airports;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAirportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAirportRequest $request)
    {
        //check if the airport is already created
        $results = Airport::where('name', $request->name)
                  ->where('code', $request->code)->get();
        
        if(count($results) > 0){
            return response()->json(['error' => 'Airport has already been created'],400);
        }else{
            $airport =  Airport::create($request->all());
            return $airport;
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Airport  $airportS
     * @return \Illuminate\Http\Response
     */
    public function show(Airport $airport)
    {
        //
        return response()->json($airport);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Airport  $airport
     * @return \Illuminate\Http\Response
     */
    public function edit(Airport $airport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAirportRequest  $request
     * @param  \App\Models\Airport  $airport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAirportRequest $request, Airport $airport)
    {
        //
        return $airport->update([
            'name' => $request->name,
            'location' => $request->location,
            'address' => $request->address,
            'code' => $request->code
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Airport  $airport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airport $airport)
    {
        //
        $airport->delete();
    }
}
