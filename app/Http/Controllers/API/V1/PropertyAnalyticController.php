<?php

namespace App\Http\Controllers\API\V1;

use App\Property;
use App\AnalyticType;
use App\PropertyAnalytic;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use Validator;

class PropertyAnalyticController extends Controller
{

    /**
     * Show analytics for a property.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $guid)
    {
        Validator::make(['uuid' => $guid], [
            'uuid' => 'required|uuid',
        ])->validate();

        $property = Property::where('guid', $guid)->firstOrFail();
        $response = $property->analytics()->get();
        return response()->json($response, 200);
    }

    /**
     * Set or update an analytic data for a property.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $guid, $analyticid)
    {
        Validator::make(['uuid' => $guid, 'analyticid' => $analyticid], [
            'uuid' => 'required|uuid',
            'analyticid' => 'required|numeric',
        ])->validate();

        // Validate value is in payload.
        $validatedData = $request->validate([
            'value' => 'required',
        ]);

        // Make sure model provided all exist.
        $property = Property::with('analytics')->where('guid', $guid)->firstOrFail();
        AnalyticType::findOrFail($analyticid);

        // Does relation exist?
        $exists = false;
        foreach ($property->analytics as $analytic) {
            if ((int) $analytic->id === (int) $analyticid) {
                $exists = true;
            }
        }

        if ($exists === false) {
            // Attach
            $property->analytics()->attach(
                $analyticid,
                ['value' => $validatedData['value']]
            );
        } else {
            // Update
            $property->analytics()->updateExistingPivot(
                $analyticid,
                ['value' => $validatedData['value']]
            );
        }

        $response = PropertyAnalytic::where('property_id', $property->id)
                                    ->where('analytic_type_id', $analyticid)
                                    ->firstOrFail();
        return response()->json($response, 200);
    }
}
