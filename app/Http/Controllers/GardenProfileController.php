<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGardenProfileRequest;
use App\Http\Requests\UpdateGardenProfileRequest;
use App\Models\GardenProfile;

class GardenProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gardenProfiles = GardenProfile::orderBy('id', 'asc')->get()->all();

        return view('garden-profile.index', compact('gardenProfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGardenProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GardenProfile $gardenProfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GardenProfile $gardenProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGardenProfileRequest $request, GardenProfile $gardenProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GardenProfile $gardenProfile)
    {
        //
    }
}
