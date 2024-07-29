<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class Staffcontroller extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'salary' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        // Create a new staff record
        Staff::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'salary' => $request->input('salary'),
            'date' => $request->input('date'),
        ]);

        return redirect()->route('main.home')->with('success', 'Staff Data Added');

    }

}
