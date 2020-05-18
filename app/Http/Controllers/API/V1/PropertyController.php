<?php

namespace App\Http\Controllers\API\V1;

use App\Property;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;

class PropertyController extends Controller
{

    /**
     * Store a newly created Property.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'suburb' => 'required|regex:/^[a-zA-Z ]+$/u|max:50',
            'state' => 'required|regex:/^[a-zA-Z ]+$/u|max:50',
            'country' => 'required|regex:/^[a-zA-Z ]+$/u|max:50',
        ]);
        $property = Property::create($validatedData);
        return response()->json($property, 201);
    }
}
