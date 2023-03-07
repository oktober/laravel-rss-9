<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_home_page_can_be_seen_by_anyone()
    {
        $response = $this->get('/');
        $response->assertOk();
        $response->assertViewIs('welcome');
    }

    public function test_that_feeds_cannot_be_accessed_by_guests()
    {
        $response = $this->get('/feeds');
        $response->assertRedirect('/login');
    }

    public function test_that_feeds_can_be_accessed_by_authenticated_user()
    {
        // create an authenticated user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/feeds');
        $response->assertOk();
        $response->assertSee('No feeds found');
        $response->assertViewIs('feeds.index');
    }

    public function test_that_feeds_create_can_be_accessed_by_authenticated_user()
    {
        // create an authenticated user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/feeds/create');
        $response->assertOk();
        $response->assertSee('New Feed');
        $response->assertViewIs('feeds.create');
    }

    public function test_that_created_feed_and_entry_can_be_seen_by_authenticated_user()
    {
        // create an authenticated user
        $user = User::factory()->create();

        // there are no feeds or entries yet
        $response = $this->actingAs($user)->get('/feeds/1');
        $response->assertStatus(404);
        $response = $this->get('/entry/1');
        $response->assertStatus(404);

        // create one feed for this user with 3 entries
        $feed = Feed::factory()->create([
            'user_id' => $user->id
        ]);
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
    }

    public function test_that_feed_created_by_one_user_cannot_be_seen_by_a_different_user()
    {
        // create an authenticated user
        $user1 = User::factory()->create();

        // create one feed for this user with 1 entry
        $feed1 = Feed::factory()->create([
            'user_id' => $user1->id
        ]);
        Entry::factory()->create([
            'feed_id' => $feed1->id
        ]);

        // create another authenticated user
        $user2 = User::factory()->create();

        // create one feed for this user with 1 entry
        $feed2 = Feed::factory()->create([
            'user_id' => $user2->id
        ]);
        Entry::factory()->create([
            'feed_id' => $feed2->id
        ]);
        
        // check that $user1 can't see $user2's feed
        $response = $this->actingAs($user1)->get('/feeds/' . $feed2->id);
        $response->assertForbidden();
        
        // check that $user2 can't see $user1's feed
        $response = $this->actingAs($user2)->get('/feeds/' . $feed1->id);
        $response->assertForbidden();
    }

    public function test_that_unassigned_route_shows_404()
    {
        // unassigned routes are showing 404
        $response = $this->get('/random');
        $response->assertStatus(404);

        // create an authenticated user
        $user = User::factory()->create();

        // unassigned routes are showing 404
        $response = $this->actingAs($user)->get('/random');
        $response->assertStatus(404);
    }
}
