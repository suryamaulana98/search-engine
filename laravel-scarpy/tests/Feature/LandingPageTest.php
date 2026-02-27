<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * Test landing page loads successfully
     */
    public function test_landing_page_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test landing page contains the search form elements
     */
    public function test_landing_page_contains_search_form(): void
    {
        $response = $this->get('/');
        $response->assertSee('Search Engine Buku');
        $response->assertSee('Cari Buku Favoritmu');
        $response->assertSee('Masukkan kata kunci...');
    }

    /**
     * Test landing page contains rank select options
     */
    public function test_landing_page_contains_rank_options(): void
    {
        $response = $this->get('/');
        $response->assertSee('value="5"', false);
        $response->assertSee('value="10"', false);
        $response->assertSee('value="20"', false);
    }

    /**
     * Test landing page has search button
     */
    public function test_landing_page_has_search_button(): void
    {
        $response = $this->get('/');
        $response->assertSee('Search');
    }

    /**
     * Test landing page uses correct view
     */
    public function test_landing_page_uses_landing_view(): void
    {
        $response = $this->get('/');
        $response->assertViewIs('landing');
    }

    /**
     * Test landing page contains jQuery and Bootstrap
     */
    public function test_landing_page_includes_dependencies(): void
    {
        $response = $this->get('/');
        $response->assertSee('jquery', false);
        $response->assertSee('bootstrap', false);
    }

    /**
     * Test landing page contains footer with author name
     */
    public function test_landing_page_contains_footer(): void
    {
        $response = $this->get('/');
        $response->assertSee('Surya Maulana Akhmad');
    }

    /**
     * Test landing page contains content div for results
     */
    public function test_landing_page_has_result_container(): void
    {
        $response = $this->get('/');
        $response->assertSee('id="content"', false);
    }
}
