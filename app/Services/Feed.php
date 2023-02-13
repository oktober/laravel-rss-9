<?php 

namespace App\Services;

use Carbon\Carbon;
use SimplePie\SimplePie;

class Feed 
{
    protected SimplePie $feed;

    public function find(string $siteURL): bool
    {
        // SimplePie library adds functionality and sanitization for handling feeds
        $this->feed = new SimplePie();
        // Disable caching for now
        $this->feed->enable_cache(false);
        $this->feed->set_feed_url($siteURL);

        return $this->feed->init();
    }

    public function getSiteURL(): string
    {
        return $this->feed->get_link();
    }

    public function feedDetails(): array
    {
        return [
            'feed_url' => $this->feed->subscribe_url(),
            'site_url' => $this->feed->get_link(),
            'site_title' => $this->feed->get_title()
        ];
    }

    public function entryDetails(): array
    {
        $entryDetails = [];

        foreach($this->feed->get_items() as $entry) {

            $entryDetails[] = [
                // TODO: Validate that this is a valid URL
                'entry_url' => $entry->get_link(),
                'entry_title' => $entry->get_title(),
                'entry_teaser' => $entry->get_description(),
                'entry_content' => $entry->get_content(),
                // Use Carbon to magically fix weirdly formatted dates
                'entry_last_updated' => new Carbon($entry->get_date('Y-m-d H:i:s')),
            ];

        }

        return $entryDetails;
    }
}