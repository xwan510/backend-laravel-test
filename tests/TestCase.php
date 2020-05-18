<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Faker\Factory as Faker;

/**
 * This is the base test class.
 * It makes sure database migration is refreshed and seeded for each test class.
 *
 * @package Tests
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $faker;

    /**
     * Set up the test
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create();
    }

    /**
     * Reset the migrations
     */
    public function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}