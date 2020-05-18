<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Property;

class PropertyTest extends TestCase
{
    /**
     * Test can add property.
     *
     * @return void
     */
    public function testAddValidProperty()
    {
        $data = [
            'suburb' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
        $response = $this->json('POST', '/api/v1/properties', $data);
        $response->assertStatus(201);
        $response->assertJson($data);
    }

    /**
     * Test add invalid property property.
     *
     * @return void
     */
    public function testAddPropertyInvalid()
    {
        $data = [
            'suburb' => 1,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
        $response = $this->json('POST', '/api/v1/properties', $data);
        $response->assertStatus(422);
    }

    /**
     * Test add property with missing attribute.
     *
     * @return void
     */
    public function testAddPropertyMissingAttribute()
    {
        $data = [
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
        $response = $this->json('POST', '/api/v1/properties', $data);
        $response->assertStatus(422);
    }
}
