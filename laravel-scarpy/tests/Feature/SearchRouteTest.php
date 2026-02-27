<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchRouteTest extends TestCase
{
    /**
     * Test search route exists and is accessible
     */
    public function test_search_route_exists(): void
    {
        $response = $this->get('/search?q=test&rank=5');
        // Should not return 404 (may fail due to Python dependency, but route should exist)
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    /**
     * Test search route is named correctly
     */
    public function test_search_route_is_named(): void
    {
        $this->assertTrue(\Route::has('search'));
    }

    /**
     * Test search route generates correct URL
     */
    public function test_search_route_url(): void
    {
        $url = route('search');
        $this->assertStringContainsString('/search', $url);
    }

    /**
     * Test search route uses GET method
     */
    public function test_search_route_uses_get_method(): void
    {
        $response = $this->post('/search', ['q' => 'test', 'rank' => 5]);
        $response->assertStatus(405); // Method Not Allowed
    }

    /**
     * Test search route without parameters (should still hit controller, not 404)
     */
    public function test_search_route_without_params(): void
    {
        $response = $this->get('/search');
        $this->assertNotEquals(404, $response->getStatusCode());
    }
}
