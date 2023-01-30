<?php 

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Feed 
{
    protected $feedURL;
    protected $siteURL;
    protected $rawXML;
    protected $xmlObject;

    public function find($siteURL)
    {
        // Remove any erroneous whitespace and trailing slash
        $this->siteURL = rtrim(trim($siteURL), '/');

        // Check if RSS feed can be found at any of these paths
        $pathOptions = [
            $this->siteURL . '/feed',
            $this->siteURL . '/rss.xml',
            $this->siteURL . '/atom.xml',
            // TODO: need to extract the domain from the URL to insert 'feed.'
            // 'feed.' . $this->siteURL
        ];
        $feedFound = false;

        foreach ($pathOptions as $path) {
            $feedResponse = HTTP::get($path);

            if ($feedResponse->ok()) {
                $this->feedURL = $path;
                $this->rawXML = $feedResponse->body();
                $feedFound = true;
                break;
            }
        }

        return $feedFound;
    }

    public function getXMLType()
    {
        // TODO: What are the security ramifications here?
        //     And how can we mitigate them?
        $this->xmlObject = simplexml_load_string($this->rawXML);

        // This returns the type of XML it is
        return $this->xmlObject->getName();
    }

    public function hasNamespace(): bool
    {
        $xmlNamespaces = $this->xmlObject->getDocNamespaces();

        return isset($xmlNamespaces) && !empty($xmlNamespaces);
    }

    public function feedDetails($typeOfXML): array
    {
        if ('rss' === $typeOfXML) {
            return [
                'feed_url' => $this->feedURL,
                'site_url' => $this->siteURL,
                'site_title' => (string) $this->xmlObject->channel->title
            ];
        } else if ('feed' === $typeOfXML) {

            // had to set a namespace for xpath to work on this XML
            $this->xmlObject->registerXPathNamespace("n", "http://www.w3.org/2005/Atom");

            return [
                'feed_url' => $this->feedURL,
                'site_url' => $this->siteURL,
                'site_title' => (string) $this->xmlObject->title
            ];
        } else {
            // we don't know what type of XML this is
            return [];
        }
    }

    public function entryDetails($typeOfXML): array
    {
        $entryDetails = [];

        if ('rss' === $typeOfXML) {

            foreach($this->xmlObject->channel->item as $entry) {
                // For the description, 
                    // turn it into a string and trim/replace extraneous whitespace
                $xmlDescription = (string) $entry->description;
                $descriptionTrimmed = $this->removeWhitespace($xmlDescription);
                // Remove all HTML tags
                $descriptionStripped = strip_tags($descriptionTrimmed);
                // Only put the first 500 characters in the teaser and add ellipses
                $entryTeaser = (!empty($descriptionStripped)) ? substr($descriptionStripped, 0, 500) . '...' : '';

                // We have to check that the namespace exists in order to run the xpath to get the value
                if ($this->hasNamespace() && isset($this->xmlObject->getDocNamespaces()['content'])) {

                    // Every rss feed so far has either <content:encoded> or just <description>
                    $xmlContent = (string) $entry->xpath('content:encoded')[0];
                    // Trim/replace extraneous whitespace
                    $contentStripped = str_replace("  ", "", trim($xmlContent));

                    $entryContent = $contentStripped;

                } else {
                    $entryContent = $descriptionTrimmed;
                }

                $entryDetails[] = [
                    // TODO: Validate that this is a valid URL
                    'entry_url' => (string) $entry->link,
                    'entry_title' => (string) $entry->title,
                    'entry_teaser' => $entryTeaser,
                    'entry_content' => $entryContent,
                    // Use Carbon to magically fix weirdly formatted dates
                    'entry_last_updated' => new Carbon($entry->pubDate),
                ];
            }
        } else if ('feed' === $typeOfXML) {

            foreach($this->xmlObject->entry as $entry) {

                $xmlDescription = (string) $entry->summary;
                $descriptionTrimmed = $this->removeWhitespace($xmlDescription);
                // Remove all HTML tags
                $descriptionStripped = strip_tags($xmlDescription);
                // Only put the first 500 characters in the teaser and add ellipses
                $entryTeaser = (!empty($descriptionStripped)) ? substr($descriptionStripped, 0, 500) . '...' : '';

                if (isset($entry->content)) {
                    $xmlContent = (string) $entry->content;
                    $contentStripped = $this->removeWhitespace($xmlContent);

                    $entryContent = $contentStripped;
                } else {
                    $entryContent = $descriptionTrimmed;
                }

                $entryDetails[] = [
                    // TODO: Validate that this is a valid URL
                    'entry_url' => (string) $entry->link->attributes()['href'],
                    'entry_title' => (string) $entry->title,
                    'entry_teaser' => $entryTeaser,
                    'entry_content' => $entryContent,
                    // Use Carbon to magically fix weirdly formatted dates
                    'entry_last_updated' => new Carbon($entry->updated),
                ];
            }
        }

        return $entryDetails;
    }

    protected function removeWhitespace($string)
    {
        return str_replace("  ", "", trim($string));
    }
}