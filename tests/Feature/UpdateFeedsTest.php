<?php

namespace Tests\Feature;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateFeedsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $feedId;
    protected $user;
    protected $url = 'https://staciefarmer.com/';

    protected function setUp(): void
    {
        parent::setUp();

        // create a user first
        $this->user = User::factory()->create();
        // create a feed for that user
        $this->actingAs($this->user)->post('/feeds/', ['site_url' => $this->url]);
        // get the ID from db
        $this->feedId = Feed::where('site_url', $this->url)->where('user_id', $this->user->id)->value('id');
    }
    
    public function test_a_feed_title_gets_renamed()
    {
        // update the site title 
        $this->put('/feeds/' . $this->feedId, ['site_title' => 'New Title']);

        // check that it was updated in the DB 
        $feed = Feed::find($this->feedId);
        $this->assertEquals('New Title', $feed->site_title);

    }
    
    public function test_an_empty_title_does_not_get_renamed()
    {
        // get the original site_title
        $original_title = Feed::find($this->feedId)->value('site_title');
        
        // try update the site title to an empty string
        $this->put('/feeds/' . $this->feedId, ['site_title' => '']);

        // check that it was not updated in the DB 
        $feed = Feed::find($this->feedId);
        $this->assertEquals($original_title, $feed->site_title);

    }
    
    public function test_one_user_cannot_update_another_users_feed()
    {
        // create a second user
        $user2 = User::factory()->create();

        // update the site title 
        $response = $this->actingAs($user2)->put('/feeds/' . $this->feedId, ['site_title' => 'New Title']);
        $response->assertForbidden();

        // check that it was not updated in the DB 
        $feed = Feed::find($this->feedId);
        $this->assertFalse('New Title' === $feed->site_title);

    }

    // test that a feed gets deleted and is no longer in the db 
    public function test_feed_gets_deleted() 
    {
        // delete the feed
        $this->delete('/feeds/' . $this->feedId)->assertRedirect('/dashboard')->assertSessionHas('success', 'Feed has been successfully deleted');
        // validate it doesn't exist in database 
        $this->assertDatabaseMissing('feeds', ['site_url' => $this->url]);
    }
    
    public function test_one_user_cannot_delete_another_users_feed()
    {
        // create a second user
        $user2 = User::factory()->create();

        // try to delete $user's feed as $user2
        $response = $this->actingAs($user2)->delete('/feeds/' . $this->feedId);
        $response->assertForbidden();

       // validate $user's feed still exists in database 
       $this->assertDatabaseHas('feeds', ['site_url' => $this->url, 'user_id' => $this->user->id]);
    }

}
