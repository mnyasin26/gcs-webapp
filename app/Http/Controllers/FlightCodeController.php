<?php

namespace App\Http\Controllers;

use App\Events\MapUpdate;
use App\Http\Requests\StoreFlightCodeRequest;
use App\Http\Requests\UpdateFlightCodeRequest;
use App\Http\Requests\UpdateMapViewRequest;
use App\Models\FlightCode;
use App\Models\TelemetriLog;
use Illuminate\Support\Facades\Session;

class FlightCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flightCode = FlightCode::orderBy('created_at', 'asc')->get()->all();
        $selectedFlightCode = FlightCode::where('selected', 1)->first()->id;

        return view('flight-code.index', compact('flightCode', 'selectedFlightCode'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('flight-code.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFlightCodeRequest $request)
    {
        $flightCode = FlightCode::create([
            'flight_code' => $request->flight_code,
        ]);

        if ($flightCode) {
            $this->select($flightCode);
            return redirect()->route('flight-code.index')->with('success', 'Flight code is created');
        } else {
            return redirect()->route('flight-code.index')->with('error', 'Flight code failed to save');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FlightCode $flightCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FlightCode $flightCode)
    {
        return view('flight-code.edit', compact('flightCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFlightCodeRequest $request, FlightCode $flightCode)
    {
        $flightCode->update($request->all());

        if ($flightCode) {
            return redirect()->route('flight-code.index')->with('success', 'Flight code is updated');
        } else {
            return redirect()->route('flight-code.index')->with('error', 'Flight code failed to Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FlightCode $flightCode)
    {
        $flightCode->delete();

        if ($flightCode) {
            return redirect()->route('flight-code.index')->with('success', 'Flight code is deleted');
        } else {
            return redirect()->route('flight-code.index')->with('error', 'Flight code failed to delete');
        }
    }

    /**
     * Select the specified flight code.
     */
    public function select(FlightCode $flightCode)
    {
        $activeFlightCode = FlightCode::where('selected', 1)->first();
        if($activeFlightCode)
        {
            $activeFlightCode->selected = 0;
            $activeFlightCode->save();
        }

        $flightCode->selected = 1;
        $flightCode->save();

        return redirect()->route('flight-code.index');
    }

    /**
     * Select the specified flight code.
     */
    public function select_view($flight_code_id)
    {
        $flightCodeExist = FlightCode::where('id', $flight_code_id)->exists();
        if($flightCodeExist){
            $flightCode = FlightCode::where('id', $flight_code_id)->first();
            
            $this->select($flightCode);
            Session::put('selectedFlightCode', $flightCode->id);
        }else{
            Session::forget('selectedFlightCode');
        }

        return redirect()->route('map.index');
    }

    // Display data for Datatables
    public function data()
    {
        $flightCode = FlightCode::orderBy('created_at', 'DESC');

        return datatables()->of($flightCode)
            ->addColumn('action', 'flight-code.action')
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->toJson();
    }
}
