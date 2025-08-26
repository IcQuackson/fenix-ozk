<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Contracts\FenixPort;
use Tests\Fakes\FakeFenix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\EnsureFenixAuth;

class CourseListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_courses_page_renders_with_fake_fenix(): void
    {
        // Skip token middleware in tests
        $this->withoutMiddleware(EnsureFenixAuth::class);

        // Swap the real adapter with our fake
        $this->app->instance(FenixPort::class, new FakeFenix());

        // Auth user
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Assert the page renders and shows our fake data
        $this->get('/courses')
            ->assertOk()
            ->assertSee('Algorithms');
    }
}
