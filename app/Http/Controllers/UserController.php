<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function sellers()
    {
        $users = User::where('type', 'seller')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.sellers', compact('users'));
    }
    public function customerSupport()
    {
        $users = User::where('type', 'customer_support')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.customer_supports', compact('users'));
    }
    public function delegates()
    {
        $users = User::where('type', 'delegate')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('users.delegates', compact('users'));
    }

    public function clients()
    {
        $users = User::where('type', 'client')
            ->orderBy('points', 'desc')
            ->get();
        return view('users.clients', compact('users'));
    }

    public function create()
    {
        $countries = Country::with('cities')->orderBy('name')->get(); // Eager load cities
        return view('users.create', compact('countries'));
    }

    public function validateData(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => 'required',
            'phone' => 'required|unique:users,phone',
            'city' => 'required|exists:cities,id',
            'country' => 'required|exists:countries,id',
        ]);

        return $data;
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return redirect()->route('users.' . $user->type . 's')->with('success', 'user was created successfully');
    }

    public function edit($uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $countries = Country::with('cities')->orderBy('name')->get(); // Eager load cities

        return view('users.edit', compact('user','countries'));
    }

    public function update(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        $data = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'phone' => [
                'required',
                'unique:users,phone,' . $user->id,
                function ($attribute, $value, $fail) use ($user) {
                    if (User::where('phone2', $value)->where('id', '!=', $user->id)->exists()) {
                        $fail('The phone number cannot match any user\'s secondary phone.');
                    }
                },
            ],
            'phone2' => [
                'nullable',
                'unique:users,phone2,' . $user->id,
                function ($attribute, $value, $fail) use ($user) {
                    if ($value && User::where('phone', $value)->where('id', '!=', $user->id)->exists()) {
                        $fail('The secondary phone number cannot match any user\'s primary phone.');
                    }
                },
            ],
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'city' => 'nullable|exists:cities,id',
            'country' => 'nullable|exists:countries,id',
        ]);
        $data['is_online'] =  $request->has('is_online') ? 1 : 0;
        $user->update($data);
        return redirect()->route('users.' . $user->type . 's')->with('success', $user->type . ' was updated successfully');
    }



    public function makeSponser(Request $request)
    {


        $user = User::findOrFail($request->user_id);
        $user->is_sponser = 1;
        $user->save();

        return redirect()->back()->with('success', 'seller' . $user->first_name . 'is a sponser now');
    }
}
