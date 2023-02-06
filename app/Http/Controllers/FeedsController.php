<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\Entry;
use App\Services\Feed as FeedService;

class FeedsController extends Controller
{
    public function index()
    {
    	$feeds = Feed::get();
    	
    	return view('feeds.index', ['feeds' => $feeds]);
    }

    public function create()
    {
    	return view('feeds.create');
    }

    public function store()
    {
        // Validate the input fields
    	request()->validate([
    		'site_url' => 'required|active_url',
    	]);

        // Check if this site already exists in the db 
        $feedExists = Feed::where('site_url', request('site_url'))->first();

        if (! $feedExists) {

            $feed = new FeedService;
            $feedFound = $feed->find(request('site_url'));

            // If we were able to find an RSS feed file
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
                    return $this->notFound();
                }

                // Redirect them to the newly created feed page
                return redirect(route('feeds.show', $createdFeed->id));

            } else {
                return $this->notFound();
            }
        } else {
            // This feed already exists, return to the create form and display error
            return redirect('/feeds/create')->withError('This feed already exists')->withInput();
        }
    }

    public function show(Feed $feed)
    {
    	return view('feeds.show', ['feed' => $feed]);
    }

    public function edit(Feed $feed)
    {
    	return view('feeds.edit', ['feed' => $feed]);
    }

    public function update(Feed $feed)
    {
        request()->validate([
    		'site_title' => 'required',
    	]);

    	$currentTime = new \DateTime();

    	$feed->update([
    		'site_title' => request('site_title'),
    		'updated_at' => $currentTime->format('Y-m-d H:i:s')
    	]);

    	// Redirect them to the feed page once entered
    	return redirect(route('feeds.show', $feed));
    }

    public function destroy(Feed $feed)
    {
    	$feed->delete();
    	
    	return redirect('/')->with('success', 'Feed has been successfully deleted');
    }

    protected function notFound()
    {
        // Can't find a feed, return to previous page and display error
        return back()->withError('We\'re not able to find an RSS feed for this site')->withInput();
    }
}
