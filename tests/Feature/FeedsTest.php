<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeedsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_valid_feed_gets_created()
    {
        $url = 'https://staciefarmer.com';

        // store the feed 
        // validate the redirect with the new feed ID
        $this->post('/feeds/', ['site_url' => $url])->assertRedirect('/feeds/1');

        // validate the database 
        $this->assertDatabaseHas('feeds', ['site_url' => $url]);
        
    }
}
