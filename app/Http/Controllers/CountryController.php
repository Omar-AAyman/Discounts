<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller {
    public function index() {
        $cities = Country::get();
        return view('cities.index', compact('cities'));
    }
    public function create() {
        return view('cities.create');
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        Country::create($request->all());
        return redirect()->route('cities.index')->with('success', 'City added');
    }
    public function edit(Country $city) {
        return view('cities.edit', compact('city'));
    }
    public function update(Request $request, Country $city) {
        $request->validate(['name' => 'required|string|max:255']);
        $city->update($request->all());
        return redirect()->route('cities.index')->with('success', 'City updated');
    }
    public function destroy(Country $city) {
        $city->delete();
        return redirect()->route('cities.index')->with('success', 'City deleted');
    }
}
