<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
    public function index(){
    	$feeds = Feed::get();
    	
    	return view('feeds.index', ['feeds' => $feeds]);
    }

    public function create(){
    	//Shows a view to create a new feed

    	return view('feeds.create');
    }

    public function store(){
        // Validate the input fields
    	$this->validateFeed();
        $siteURL = request('site_url');
        
        // Remove ending slash in URL if there
        if (Str::endsWith($siteURL, '/')) {
            $siteURL = Str::replaceLast('/', '', $siteURL);
        }

        // Check if RSS feed is at {$siteURL}/feed
        $feedURL = $siteURL . '/feed';
        $feedResponse = HTTP::get($feedURL);

        //If we were able to find an RSS feed file
        if ($feedResponse->ok()) {

            // TODO: What are the security ramifications here?
                // And how can we mitigate them?

            $feedXML = str_replace(':encoded', '', file_get_contents($feedURL)); //Get the file as a string and remove ':encoded' from XML elements so it formats properly
            $xmlObject = simplexml_load_string($feedXML, 'SimpleXMLElement', LIBXML_NOCDATA); //Load it as an XML object without CDATA
            $feedJson = json_encode($xmlObject); //Turn it into a JSON object
            $feedCollection = collect(json_decode($feedJson, true)); //Turn it into an array, then into a collection

            // Insert the feed details
            $createdFeed = Feed::create([
                'feed_url' => $siteURL . '/feed',
                'site_url' => $siteURL,
                'site_title' => request('site_title')
            ]);

            // Looking for posts/entries in different types of files
            if (isset($feedCollection['entry'])) { //Found a post in atom+xml

                //Insert the entries
                foreach($feedCollection['entry'] as $post) {
                    // Use Carbon to magically fix weirdly formatted dates
                    $updated = new Carbon($post['updated']);

                    Entry::create([
                        'feed_id' => $createdFeed->id,
                        'entry_url' => $post['id'],
                        'entry_title' => $post['title'],
                        'entry_teaser' => $post['summary'],
                        'entry_content' => $post['content'],
                        'entry_last_updated' => $updated,
                    ]);

                }
            } elseif (isset($feedCollection['channel']['item'])) { //Found a post in rss+xml

                // Insert the entries
                foreach($feedCollection['channel']['item'] as $post) {

                    // Use Carbon to magically fix weirdly formatted dates
                    $updated = new Carbon($post['pubDate']);

                    Entry::create([
                        'feed_id' => $createdFeed->id,
                        'entry_url' => $post['link'],
                        'entry_title' => $post['title'],
                        'entry_teaser' => $post['description'],
                        'entry_content' => $post['content'],
                        'entry_last_updated' => $updated,
                    ]);

                }

            } else {

                // If feed not available, return to previous page and display error
                //return back()->withError('We\'re not able to find an RSS feed for this site')->withInput();
                dd('Not finding any posts');

            }

            // Redirect them to the feed page once entered
            return redirect(route('feeds.show', $createdFeed->id));

        } else {

            // If feed not available, return to previous page and display error
            return back()->withError('We\'re not able to find an RSS feed for this site')->withInput();

        }
    }

    public function show(Feed $feed){
    	
    	return view('feeds.show', ['feed' => $feed]);
    }

    public function edit(Feed $feed){
    	// Show a view to edit a feed

    	return view('feeds.edit', ['feed' => $feed]);
    }

    public function update(Feed $feed){
    	$this->validateFeed();

    	$currentTime = new \DateTime();

    	$feed->update([
            // only need this line if we're allowing them to update the feed/XML URL
    		// 'feed_url' => request('feed_url'),
    		'site_url' => request('site_url'),
    		'site_title' => request('site_title'),
    		'last_updated' => $currentTime->format('Y-m-d H:i:s')
    	]);

        // Get XML file from feed_url
        //walk through it and use the feeds to update the entries table
            //if entry doesn't exist 
                //insert into entries
            //OR updated date it after current updated date
                //update the entry

    	// Redirect them to the feed page once entered
    	return redirect(route('feeds.show', $feed));
    }

    public function destroy(Feed $feed){
    	$feed->delete();
    	
    	return redirect('/')->with('success', 'Feed has been successfully deleted');
    }

    protected function validateFeed() {
        // TODO: Shouldn't the validation be in the model?
        // And this function should probably just go inline under store()
    	return request()->validate([
    		'site_title' => 'required',
    		'site_url' => 'required|active_url',
    	]);
    }
}
