<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:location-list|location-create|location-edit|location-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:location-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:location-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:location-delete'], ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::latest()->get();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:locations,name'
        ]);

        Location::create($request->only('name'));
        
        return redirect()->route('locations.index')->with('success', 'Location created!');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|unique:locations,name,' . $location->id,
        ]);

        $location->update($request->only('name'));

        return redirect()->route('locations.index')->with('success', 'লোকেশন আপডেট হয়েছে।');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('error', 'লোকেশন মুছে ফেলা হয়েছে।');
    }
}
