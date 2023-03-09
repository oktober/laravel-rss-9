<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\Entry;
use App\Models\User;
use App\Services\Feed as FeedService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedsController extends Controller
{
    public function index()
    {
        // show all feeds that belong to this user that have entries
        $feeds = Feed::whereBelongsTo(auth()->user())->with('entries')->get();
    	
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
    		'site_url' => [
                'required', 
                'active_url', 
                'max:255', 
                // need to figure out how to run this rule after we grab the site_url from the db
                    // otherwise, we can't guarantee it's the right formatting
                    // but we need to do some validation first before we run FeedService::find
                // Rule::unique(Feed::class)
                //     ->where(fn ($query) => $query->where('user_id', request()->user()->id))
            ],
    	]);

        $feed = new FeedService;
        $feedFound = $feed->find(request('site_url'));

        // If we were able to find a feed for this site
        if ($feedFound) {

            // Check if this site already exists in the db 
            $feedAlreadyExists = auth()->user()->feeds->where('site_url', $feed->getSiteURL())->first();
            if ($feedAlreadyExists) {
                return $this->backWithError('This feed already exists');
            }

            // add the user_id to the beginning of the feedDetails array
            $feedDetails = array_merge(['user_id' => auth()->id()], $feed->feedDetails());
            // Insert the feed details
            $createdFeed = Feed::create($feedDetails);

            $allEntries = $feed->entryDetails();
            foreach ($allEntries as $entry) {
                // Add the feed_id to the beginning of each array
                $entryDetails = array_merge(['feed_id' => $createdFeed->id], $entry);
                // Insert the entry details
                Entry::create($entryDetails);
            }

            // Redirect them to the newly created feed page
            return redirect(route('feeds.show', $createdFeed->id));

        } else {
            return $this->backWithError("We're not able to find an RSS feed for this site.");
        }
    }

    public function show(Feed $feed)
    {
        if ($feed->user_id !== auth()->id()){
            abort(403);
        }

        return view('feeds.show', ['feed' => $feed]);
    }

    public function edit(Feed $feed)
    {
        if ($feed->user_id !== auth()->id()){
            abort(403);
        }
        
    	return view('feeds.edit', ['feed' => $feed]);
    }

    public function update(Feed $feed)
    {
        if ($feed->user_id !== auth()->id()){
            abort(403);
        }
        
        request()->validate([
    		'site_title' => 'required|string|max:255',
    	]);

    	$feed->update([
    		'site_title' => request('site_title')
    	]);

    	// Redirect them to the feed page once entered
    	return redirect(route('feeds.show', $feed));
    }

    public function destroy(Feed $feed)
    {
        if ($feed->user_id !== auth()->id()){
            abort(403);
        }

    	$feed->delete();
    	
    	return redirect('/dashboard')->with('success', 'Feed has been successfully deleted');
    }

    protected function backWithError(string $error)
    {
        // Can't find a feed, return to previous page and display error
        return redirect('/feeds/create')->withError($error)->withInput();
    }
}
