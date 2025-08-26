<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\EnsureFenixAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Contracts\FenixPort;
use Tests\Fakes\FakeFenix;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    public function test_the_application_returns_a_successful_response(): void
    {
        // Bypass Fenix middleware for this smoke test
        $this->withoutMiddleware(EnsureFenixAuth::class);

        $this->app->instance(FenixPort::class, new FakeFenix());


        // Act as a logged-in user so 'auth' passes (no login route needed)
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
