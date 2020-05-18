<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Property;
use App\AnalyticType;

class PropertyAnalyticsTest extends TestCase
{
    /**
     * Test can add property analytic.
     *
     * @return void
     */
    public function testAddPropertyAnalytic()
    {
        $property = factory(Property::class)->create();
        $analyticType = factory(AnalyticType::class)->create();
        $data = ['value' => (String) $this->faker->randomNumber(3)];
        $response = $this->json('PUT', '/api/v1/properties/'.$property->guid.'/analytics/'.$analyticType->id, $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => $data]);
    }


    /**
     * Test can update property analytic.
     *
     * @return void
     */
    public function testUpdatePropertyAnalytic()
    {
        $property = factory(Property::class)->create();
        $analyticType = factory(AnalyticType::class)->create();

        // Create an analytic for a property.
        $data = ['value' => (String) $this->faker->randomNumber(3)];
        $response = $this->json('PUT', '/api/v1/properties/'.$property->guid.'/analytics/'.$analyticType->id, $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => $data]);

        // Update it with a new value.
        $data = ['value' => $this->faker->randomNumber(3)];
        $response = $this->json('PUT', '/api/v1/properties/'.$property->guid.'/analytics/'.$analyticType->id, $data);
        $response->assertStatus(200);
        $response->assertJson(['data' => $data]);
    }

    /**
     * Test get all analytics for a property.
     *
     * @return void
     */
    public function testGetPropertyAnalytics()
    {
        // Create analytic types and a property.
        $property = factory(Property::class)->create();
        $analyticTypes = factory(AnalyticType::class, $this->faker->randomDigitNotNull)->create();

        // Set analyics for the property.
        $expectedData = [];
        foreach ($analyticTypes as $type) {
            $postData = ['value' => $this->faker->randomNumber(3)];
            $expectedData[] = [
                'name' => $type->name,
                'units' => $type->units,
                'is_numeric' => (int) $type->is_numeric,
                'num_decimal_places' => (int) $type->num_decimal_places,
                'value' => (String) $postData['value'],
            ];
            $response = $this->json('PUT', '/api/v1/properties/'.$property->guid.'/analytics/'.$type->id, $postData);
            $response->assertStatus(200);
            $response->assertJson(['data' => $postData]);
        }

        // Test get all analytics for a property.
        $response = $this->json('GET', '/api/v1/properties/'.$property->guid.'/analytics');
        $response->assertStatus(200);
        $response->assertJson($expectedData);
    }

    /**
     * Test invalid queries for property analytics.
     *
     * @return void
     */
    public function testPropertyAnalyticsInvalid()
    {
        // Property non-exists.
        $response = $this->json('GET', '/api/v1/properties/non-exist/analytics');
        $response->assertStatus(404);

        $property = factory(Property::class)->create();
        $analyticType = factory(AnalyticType::class)->create();
        $data = ['value' => (String) $this->faker->randomNumber(3)];

        // Empty value.
        $response = $this->json('PUT', '/api/v1/properties/'.$property->guid.'/analytics/'.$analyticType->id);
        $response->assertStatus(400);

        // Analytic Type non-exists.
        $response = $this->json('PUT', '/api/v1/properties/'.$property->guid.'/analytics/non-exist');
        $response->assertStatus(404);

        // Property non-exists.
        $response = $this->json('PUT', '/api/v1/properties/non-exists/analytics/'.$analyticType->id, $data);
        $response->assertStatus(404);
    }
}
