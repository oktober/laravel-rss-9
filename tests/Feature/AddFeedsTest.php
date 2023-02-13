<?php

namespace Tests\Feature;

use App\Models\Feed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddFeedsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_a_valid_feed_gets_created()
    {
        // all these sites should work, but they have different types of XML feeds
        $validSites = [
            'https://staciefarmer.com/', // located @ /feed and uses atom
            'https://laravel-news.com/', // located @ /feed and uses rss
            'https://xkcd.com/', // located @ /rss.xml or /atom.xml
            // find & test a site that uses URL/atom.xml only
        ];

        foreach ($validSites as $url) {

            // store the feed 
            $this->post('/feeds/', ['site_url' => $url])->assertRedirectContains('/feeds/');
    
            // validate the database 
            $this->assertDatabaseHas('feeds', ['site_url' => $url]);
    
            // get the ID from db
            $feedId = Feed::where('site_url', $url)->value('id');
    
            $response = $this->get('/feeds/' . $feedId);
            // check that the page exists
            $response->assertOk();

        }
        
    }


    // test how a valid URL with a missing feed or an invalid URL does not get created 
    public function test_an_invalid_feed_does_not_get_created()
    {
        // all these sites should work, but they have different types of XML feeds
        $invalidFeeds = [
            'https://laravel.com/', // valid URL but doesn't have a feed
            'https://laravel.cde/', // invalid URL
        ];

        foreach ($invalidFeeds as $url) {
            // try to store the feed 
            $this->post('/feeds/', ['site_url' => $url]);

            // validate it doesn't exist in database 
            $this->assertDatabaseMissing('feeds', ['site_url' => $url]);

            // try to get the ID from db
            $feedId = Feed::where('site_url', $url)->value('id');
            // if it wasn't created, it should be null
            $this->assertNull($feedId);
        }
    }

    public function test_a_duplicate_feed_does_not_get_added()
    {
        $url = 'https://staciefarmer.com/';

        // add this feed to the database 
        $this->post('/feeds/', ['site_url' => $url]);

        // validate the database has a feed with this URL 
        $this->assertDatabaseHas('feeds', ['site_url' => $url]);

        // store the feed 
        $this->post('/feeds/', ['site_url' => $url])
            ->assertRedirectContains('/feeds/create')
            ->assertSessionHas([
                'error' =>'This feed already exists'
            ]);
        
    }
}
