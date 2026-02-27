<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * Test all defined routes are valid
     */
    public function test_all_routes_have_valid_actions(): void
    {
        $routes = \Route::getRoutes();
        foreach ($routes as $route) {
            $action = $route->getAction();
            $this->assertNotNull($action);
        }
    }

    /**
     * Test welcome/landing route exists
     */
    public function test_home_route_returns_success(): void
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    /**
     * Test non-existent route returns 404
     */
    public function test_non_existent_route_returns_404(): void
    {
        $response = $this->get('/non-existent-page');
        $response->assertStatus(404);
    }

    /**
     * Test search route exists with correct name
     */
    public function test_search_route_name_resolves(): void
    {
        $url = route('search');
        $this->assertNotEmpty($url);
    }
}
