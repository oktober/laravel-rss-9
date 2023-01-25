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
    	return view('feeds.create');
    }

    public function store(){
        // Validate the input fields
    	$this->validateFeed();
        $siteURL = trim(request('site_url'));
        
        // Remove ending slash in URL if there
        if (Str::endsWith($siteURL, '/')) {
            $siteURL = Str::replaceLast('/', '', $siteURL);
        }

        // Check if RSS feed can be found at any of these paths
        // TODO: Could also be feed.siteURL?
        $pathOptions = [
            'feed',
            'rss.xml',
            'atom.xml'
        ];
        $feedURL = '';
        $feedFound = false;

        foreach ($pathOptions as $path) {
            $feedResponse = HTTP::get($siteURL . '/' . $path);

            if ($feedResponse->ok()) {
                $feedURL = $siteURL . '/' . $path;
                $feedFound = true;
                break;
            }
        }

        //If we were able to find an RSS feed file
        if ($feedFound) {
            $rawXML = $feedResponse->body();

            // TODO: What are the security ramifications here?
            //     And how can we mitigate them?
            $xmlObject = simplexml_load_string($rawXML);

            $typeOfXML = $xmlObject->getName();

            if ('rss' === $typeOfXML) {
                $xmlNamespaces = $xmlObject->getDocNamespaces();
                $hasNamespace = isset($xmlNamespaces) && !empty($xmlNamespaces);

                $siteTitle = (string) $xmlObject->channel->title;

                // Insert the feed details
                $createdFeed = Feed::create([
                    'feed_url' => $feedURL,
                    'site_url' => $siteURL,
                    'site_title' => $siteTitle
                ]);

                //Insert the entries for that feed
                foreach($xmlObject->channel->item as $entry) {

                    // For the description, 
                        // turn it into a string and trim/replace extraneous whitespace
                    $xmlDescription = (string) $entry->description;
                    $descriptionTrimmed = str_replace("  ", "", trim($xmlDescription));
                    // Remove all HTML tags
                    $descriptionStripped = strip_tags($descriptionTrimmed);
                    // Only put the first 500 characters in the teaser and add ellipses
                    $entryTeaser = (!empty($descriptionStripped)) ? substr($descriptionStripped, 0, 500) . '...' : '';

                    // We have to check that the namespace exists before in order to run the xpath to get the value
                    if ($hasNamespace && isset($xmlNamespaces['content'])) {
                        // for the content, 
                            // turn it into a string, trim/replace extraneous whitespace

                        // Every rss feed so far has either <content:encoded> or just <description>
                        $xmlContent = (string) $entry->xpath('content:encoded')[0];
                        $contentStripped = str_replace("  ", "", trim($xmlContent));

                        $entryContent = $contentStripped;
                    } else {
                        $entryContent = $descriptionTrimmed;
                    }

                    // Use Carbon to magically fix weirdly formatted dates
                    $updated = new Carbon($entry->pubDate);


                    // Insert the post/entry details
                    Entry::create([
                        'feed_id' => $createdFeed->id,
                        // TODO: Validate that this is a valid URL
                        'entry_url' => (string) $entry->link,
                        'entry_title' => (string) $entry->title,
                        'entry_teaser' => $entryTeaser,
                        'entry_content' => $entryContent,
                        'entry_last_updated' => $updated,
                    ]);

                }
            } else if ('feed' === $typeOfXML) { // atom type of XML

                // had to set a namespace for xpath to work on this XML
                $xmlObject->registerXPathNamespace("n", "http://www.w3.org/2005/Atom");

                // grab the feed_url and site_title from the feed itself
                $siteTitle = (string) $xmlObject->title;

                // Insert the feed details
                $createdFeed = Feed::create([
                    'feed_url' => $feedURL,
                    'site_url' => $siteURL,
                    'site_title' => $siteTitle
                ]);

                //Insert the entries for that feed
                foreach($xmlObject->entry as $entry) {

                    // for the description, 
                        // turn it into a string and trim/replace extraneous whitespace
                    $xmlDescription = (string) $entry->summary;
                    $descriptionTrimmed = str_replace("  ", "", trim($xmlDescription));
                    // Remove all HTML tags
                    $descriptionStripped = strip_tags($xmlDescription);
                    // Only put the first 500 characters in the teaser and add ellipses
                    $entryTeaser = (!empty($descriptionStripped)) ? substr($descriptionStripped, 0, 500) . '...' : '';

                    if (isset($entry->content)) {
                        // for the content, 
                            // turn it into a string, trim/replace extraneous whitespace
                        $xmlContent = (string) $entry->content;
                        $contentStripped = str_replace("  ", "", trim($xmlContent));

                        $entryContent = $contentStripped;
                    } else {
                        $entryContent = $descriptionTrimmed;
                    }

                    // Use Carbon to magically fix weirdly formatted dates
                    $updated = new Carbon($entry->updated);

                    // Insert the post/entry details
                    Entry::create([
                        'feed_id' => $createdFeed->id,
                        // TODO: Validate that this is a valid URL
                        'entry_url' => (string) $entry->link->attributes()['href'],
                        'entry_title' => (string) $entry->title,
                        'entry_teaser' => $entryTeaser,
                        'entry_content' => $entryContent,
                        'entry_last_updated' => $updated,
                    ]);
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
