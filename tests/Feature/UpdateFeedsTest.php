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
        $this->actingAs($this->user)->put('/feeds/' . $this->feedId, ['site_title' => 'New Title']);

        // check that it was updated in the DB 
        $feed = Feed::find($this->feedId);
        $this->assertEquals('New Title', $feed->site_title);

    }
    
    public function test_an_empty_title_does_not_get_renamed()
    {
        // get the original site_title
        $original_title = Feed::find($this->feedId)->value('site_title');
        
        // try update the site title to an empty string
        $this->actingAs($this->user)->put('/feeds/' . $this->feedId, ['site_title' => '']);

        // check that it was not updated in the DB 
        $feed = Feed::find($this->feedId);
        $this->assertEquals($original_title, $feed->site_title);

    }

    // test that a feed gets deleted and is no longer in the db 
    public function test_feed_gets_deleted() 
    {
        // delete the feed
        $this->delete('/feeds/' . $this->feedId)->assertRedirect('/')->assertSessionHas('success', 'Feed has been successfully deleted');
        // validate it doesn't exist in database 
        $this->assertDatabaseMissing('feeds', ['site_url' => $this->url]);
    }

}
