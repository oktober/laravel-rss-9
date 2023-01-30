<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\Entry;
use App\Services\Feed as ServicesFeed;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class FeedsController extends Controller
{
    public function index(){
    	$feeds = Feed::get();
    	
    	return view('feeds.index', ['feeds' => $feeds]);
    }

    public function create(){
    	return view('feeds.create');
    }

    public function store(){
        // Validate the input fields
    	$this->validateFeed();

        $feed = new ServicesFeed;
        $feedFound = $feed->find(request('site_url'));

        //If we were able to find an RSS feed file
        if ($feedFound) {
            $typeOfXML = $feed->getXMLType();
            $feedDetails = $feed->feedDetails($typeOfXML);

            if (count($feedDetails) > 0) {

                // Insert the feed details
                $createdFeed = Feed::create($feedDetails);

                $allEntries = $feed->entryDetails($typeOfXML);

                foreach ($allEntries as $entry) {
                    // Add the feed_id to the beginning of each array
                    $entryDetails = array_merge(['feed_id' => $createdFeed->id], $entry);
                    // Insert the entry details
                    Entry::create($entryDetails);
                }
            
            } else {
                // Can't find a feed, return to previous page and display error
                return back()->withError('We\'re not able to find an RSS feed for this site')->withInput();
            }

            // Redirect them to the newly created feed page
            return redirect(route('feeds.show', $createdFeed->id));

        } else {
            // Can't find a feed, return to previous page and display error
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
