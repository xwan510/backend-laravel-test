<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Property;

class PropertyAnalyticsReportController extends Controller
{

    /**
     * Report analytics summary for given property filter.
     * Performance should be considered for mass reporting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Allowed white list filters.
        $validatedData = $request->validate([
            'filter' => [
                'required',
                Rule::in(['state', 'country', 'suburb']),
            ],
            'value' => 'required|regex:/^[a-zA-Z ]+$/u|max:50',
        ]);

        // Get total number of properties.
        $total = (int) Property::where($validatedData['filter'], '=', $validatedData['value'])->count();

        // Use 1 raw query to improve performace.
        // percentile_cont is avaialble in most modern DB to calc median.
        $response = DB::table('properties')
                        ->leftJoin('property_analytics', 'properties.id', '=', 'property_analytics.property_id')
                        ->join('analytic_types', 'analytic_types.id', '=', 'property_analytics.analytic_type_id')
                        ->where('properties.'.$validatedData['filter'], '=', $validatedData['value'])
                        ->select(DB::raw('
                                        analytic_types.id,
                                        analytic_types.name,
                                        analytic_types.units,
                                        analytic_types.is_numeric,
                                        analytic_types.num_decimal_places,
                                        min(cast(value as float)) as min,
                                        max(cast(value as float)) as max,
                                        percentile_cont(0.5) within group (order by cast(value as float)) as median,
                                        concat(
                                            cast(round(
                                                count(value)::decimal * 100 / '.$total.',
                                                2
                                            ) as float),
                                            \'%\'
                                        ) as has_value,
                                        concat(
                                            cast(round(
                                                ('.$total.' - count(value)::decimal) * 100 / '.$total.',
                                                2
                                            ) as float),
                                            \'%\'
                                        ) as has_no_value
                                        '))
                        ->groupBy('analytic_types.id')
                        ->get();
        return response()->json($response, 200);
    }
}
