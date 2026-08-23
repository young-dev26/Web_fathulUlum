<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_public_navigation_destinations_are_available(): void
    {
        foreach (['mi', 'mts', 'ppdb', 'ppdb.register', 'news.show'] as $routeName) {
            $url = $routeName === 'news.show'
                ? route($routeName, 0)
                : route($routeName);

            $this->get($url)->assertOk();
        }
    }

    public function test_unknown_news_article_returns_not_found(): void
    {
        $this->get(route('news.show', 999))->assertNotFound();
    }
}
