<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Property;
use App\AnalyticType;

class PropertyAnalyticsSummaryTest extends TestCase
{
    /**
     * Test get property analytics summary.
     *
     * @return void
     */
    public function testGetPropertyAnalyticsSummary()
    {
        // Create a random number of analytic types and properties.
        $properties = factory(Property::class, $this->faker->randomNumber(3))->create();
        $analyticTypes = factory(AnalyticType::class, $this->faker->randomDigitNotNull)->create();

        // Set analyics for all properties.
        $expectedData = [];
        foreach ($analyticTypes as $type) {
            foreach ($properties as $property) {
                $response = $property->analytics()->attach($type->id, ['value' => $this->faker->randomNumber(3)]);
                $response->assertStatus(200);
                $response->assertJson(['data' => $data]);
            }
        }

        // Create some properties without any analytics.
        $propertiesWithoutAnalytics = factory(Property::class, $this->faker->randomNumber(3))->create();

        // Just use first property as the test case.
        $testProperty = Property::find(1);
        $this->assertTrue(isset($testProperty));

        // Loop through each filter and analytic type to get min/max etc.
        $filters = ['suburb', 'state', 'country'];
        $analyticTypes = AnalyticType::all();
        foreach ($filters as $filter) {
            $expectedData = [];
            $filterValue = $testProperty->{$filter};
            $totalProperties = Property::where($filter, '=', $filterValue)->count();
            foreach ($analyticTypes as $type) {
                /*
                Get all properties matching filter then apply Collection class's aggr methods.
                The application uses DB raw queries, so we verify it with another method.
                */
                $collection = $type->properties()->where($filter, '=', $filterValue)->get();
                $maxValue = $collection->max('pivot.value');
                $minValue = $collection->min('pivot.value');
                $medianValue = $collection->median('pivot.value');
                $hasValueCount = count($collection);
                $hasValuePerc = round($hasValueCount / ($totalProperties / 100), 2);
                $hasNoValuePerc = round(($totalProperties - $hasValueCount) / ($totalProperties / 100), 2);
                $expectedData[] = [
                    'name' => $type->name,
                    'units' => $type->units,
                    'is_numeric' => (int) $type->is_numeric,
                    'num_decimal_places' => (int) $type->num_decimal_places,
                    'min' => $minValue,
                    'max' => $maxValue,
                    'median' => $medianValue,
                    'hasValue' => $hasValuePerc.'%',
                    'hasNoValue' => $hasNoValuePerc.'%',
                ];
            }
            $response = $this->json('GET', '/api/v1/report/properties/analytics', [$filter => $filterValue]);
            $response->assertStatus(200);
            $response->assertJson($expectedData);
        }
    }
}
