<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

class LandingControllerTest extends TestCase
{
    /**
     * Test LandingController class exists
     */
    public function test_landing_controller_class_exists(): void
    {
        $this->assertTrue(class_exists(\App\Http\Controllers\LandingController::class));
    }

    /**
     * Test LandingController has search method
     */
    public function test_landing_controller_has_search_method(): void
    {
        $reflection = new ReflectionClass(\App\Http\Controllers\LandingController::class);
        $this->assertTrue($reflection->hasMethod('search'));
    }

    /**
     * Test LandingController search method is public
     */
    public function test_search_method_is_public(): void
    {
        $reflection = new ReflectionClass(\App\Http\Controllers\LandingController::class);
        $method = $reflection->getMethod('search');
        $this->assertTrue($method->isPublic());
    }

    /**
     * Test LandingController search method accepts Request parameter
     */
    public function test_search_method_accepts_request(): void
    {
        $reflection = new ReflectionClass(\App\Http\Controllers\LandingController::class);
        $method = $reflection->getMethod('search');
        $params = $method->getParameters();

        $this->assertCount(1, $params);
        $this->assertEquals('request', $params[0]->getName());
    }

    /**
     * Test LandingController extends base Controller
     */
    public function test_controller_extends_base(): void
    {
        $reflection = new ReflectionClass(\App\Http\Controllers\LandingController::class);
        $this->assertEquals(
            'App\Http\Controllers\Controller',
            $reflection->getParentClass()->getName()
        );
    }
}
