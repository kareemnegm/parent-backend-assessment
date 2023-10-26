<?php

namespace Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $userService;
    public function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService ();
    }


    public function testListUsersWithNoFilters()
    {
        // Test when no filters are applied, it should return all data.

        $request = [];
        $result = $this->userService->listUsers($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        $this->assertArrayHasKey('data', $result);
        $this->assertNotEmpty($result['data']);
    }

    public function testListUsersWithNameFilter()
    {
        // Test when a name filter is applied, it should return matching data.

        $request = ['name' => 'someName'];
        $result = $this->userService->listUsers($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        $this->assertArrayHasKey('data', $result);
        $this->assertEmpty($result['data']);
    }
    public function testListUsersWithNameFilter_true()
    {
        // Test when a name filter is applied, it should return matching data.

        $request = ['name' => 'DataProviderX'];
        $result = $this->userService->listUsers($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        $this->assertArrayHasKey('data', $result);
        $this->assertNotEmpty($result['data']);
    }

    public function testListUsersWithCurrencyFilter()
    {
        // Test when a currency filter is applied, it should return matching data.

        $request = ['currency' => 'USD'];
        $result = $this->userService->listUsers($request);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('data', $result);
        $this->assertNotEmpty($result['data']);
    }

    // Write similar tests for other filter scenarios...

    public function testListUsersWithInvalidStatusFilter()
    {
        // Test when an invalid status filter is applied, it should return an empty data array.

        $request = ['status' => 'invalid_status'];
        $result = $this->userService->listUsers($request);

        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        $this->assertArrayHasKey('data', $result);
        $this->assertEmpty($result['data']);
    }
}
