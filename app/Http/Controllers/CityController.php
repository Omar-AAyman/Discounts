<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;

class CityController extends Controller {
    public function index() {
        $areas = City::with('country')->get();
        return view('areas.index', compact('areas'));
    }
    public function create() {
        $cities = Country::all();
        return view('areas.create', compact('cities'));
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255', 'country_id' => 'required|exists:countries,id']);
        City::create($request->all());
        return redirect()->route('areas.index')->with('success', 'Area added');
    }
    public function edit(City $area) {
        $cities = Country::all();
        return view('areas.edit', compact('area', 'cities'));
    }
    public function update(Request $request, City $area) {
        $request->validate(['name' => 'required|string|max:255', 'country_id' => 'required|exists:countries,id']);
        $area->update($request->all());
        return redirect()->route('areas.index')->with('success', 'Area updated');
    }
    public function destroy(City $area) {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted');
    }
}
