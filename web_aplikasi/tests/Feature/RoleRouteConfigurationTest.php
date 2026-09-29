<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoleRouteConfigurationTest extends TestCase
{
    public function test_all_configured_roles_keep_their_dashboard_routes(): void
    {
        foreach (array_keys(config('roles.routes', [])) as $prefix) {
            $this->assertNotNull(
                app('router')->getRoutes()->getByName($prefix . '.dashboard'),
                "Dashboard route untuk role {$prefix} tidak ditemukan."
            );
        }
    }

    public function test_vendor_web_routes_remain_available(): void
    {
        $routes = app('router')->getRoutes();

        $this->assertNotNull($routes->getByName('vendor_tiang.dashboard'));
        $this->assertNotNull($routes->getByName('vendor_konstruksi.dashboard'));
    }
}
