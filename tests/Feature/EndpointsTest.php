<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Feed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_all_endpoints_work()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Blog Feed');

        $response = $this->get('/feeds');
        $response->assertStatus(200);
        $response->assertSee('No feeds found');

        $response = $this->get('/feeds/create');
        $response->assertStatus(200);
        $response->assertSee('New Feed');

        $response = $this->get('/feeds/1');
        $response->assertStatus(404);
        $response = $this->get('/entry/1');
        $response->assertStatus(404);

        // create one feed with 3 entries
        $feed = Feed::factory()->create();
        Entry::factory(3)->create([
            'feed_id' => $feed->id
        ]);
        $response = $this->get('/feeds/1');
        $response->assertStatus(200);

        $response = $this->get('/entry/1');
        $response->assertStatus(200);

        $response = $this->get('/random');
        $response->assertStatus(404);
    }
}
