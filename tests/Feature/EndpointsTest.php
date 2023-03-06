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
        // home page is up and showing the right view
        // $response = $this->get('/');
        // $response->assertOk();
        // $response->assertSee('Blog Feed');
        // $response->assertViewIs('home');

        // /feeds page is up and showing the right view
        $response = $this->get('/feeds');
        $response->assertOk();
        $response->assertSee('No feeds found');
        $response->assertViewIs('feeds.index');

        // /feeds/create page is up and showing the right view
        $response = $this->get('/feeds/create');
        $response->assertOk();
        $response->assertSee('New Feed');
        $response->assertViewIs('feeds.create');

        // there are no feeds or entries yet
        $response = $this->get('/feeds/1');
        $response->assertStatus(404);
        $response = $this->get('/entry/1');
        $response->assertStatus(404);

        // create one feed with 3 entries
        $feed = Feed::factory()->create();
        Entry::factory(3)->create([
            'feed_id' => $feed->id
        ]);
        $response = $this->get('/feeds/' . $feed->id);
        // this feed has been created and is showing the right view
        $response->assertOk();
        $response->assertViewIs('feeds.show');

        // get the first entry from this feed
        $entry = Entry::where('feed_id', $feed->id)->first();
        $response = $this->get('/entry/' . $entry->id);
        // this entry has been created and is showing the right view
        $response->assertOk();
        $response->assertViewIs('entries.show');

        // unassigned routes are showing 404
        $response = $this->get('/random');
        $response->assertStatus(404);
    }
}
