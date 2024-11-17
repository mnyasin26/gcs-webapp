<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppRequest;
use App\Http\Requests\UpdateAppRequest;
use App\Models\App;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppRequest $request, App $app)
    {
        $app = App::firstOrNew();

        if ($app->image) {
            Storage::disk('public')->delete($app->image);
        }

        $app->name = $request->name;
        $app->version = $request->version;
        $app->description = $request->description;
        $path = Storage::disk('public')->put('images', $request->file('image'));
        $app->image = $path;
        $app->save();

        return redirect(route('setting.index'))->with('success', 'App info is updated');
    }
}
