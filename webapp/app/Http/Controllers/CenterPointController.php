<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCenterPointRequest;
use App\Http\Requests\UpdateCenterPointRequest;
use App\Models\CenterPoint;
use DataTable;
use Illuminate\Http\Request;

class CenterPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('center-point.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $centrePoint = CenterPoint::count();
        if ($centrePoint < 1) {
            # code...
            return view('center-point.create');
        } else {
            return redirect()->route('center-point.index')
            ->with('abort', 'You can only input 1 data center point, please delete the previous data for new coordinates');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCenterPointRequest $request)
    {
        $centerPoint = CenterPoint::create([
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
        ]);

        if ($centerPoint) {
            return redirect()->route('center-point.index')->with('success', 'Center point is created');
        } else {
            return redirect()->route('center-point.index')->with('error', 'Center point failed to save');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CenterPoint $centerPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CenterPoint $centerPoint)
    {
        return view('center-point.edit', compact('centerPoint'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCenterPointRequest $request, CenterPoint $centerPoint)
    {
        $centerPoint->update($request->all());

        if ($centerPoint) {
            return redirect()->route('center-point.index')->with('success', 'Center point is updated');
        } else {
            return redirect()->route('center-point.index')->with('error', 'Center point failed to Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CenterPoint $centerPoint)
    {
        $centerPoint->delete();

        if ($centerPoint) {
            return redirect()->route('center-point.index')->with('success', 'Center point is deleted');
        } else {
            return redirect()->route('center-point.index')->with('error', 'Center point failed to delete');
        }
    }

    // Display data for Datatables
    public function data()
    {
        $centerPoint = CenterPoint::orderBy('created_at', 'DESC');

        return datatables()->of($centerPoint)
            ->addColumn('action', 'center-point.action')
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->toJson();
    }
}
