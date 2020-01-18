<?php

namespace App\Services;

use App\Clients\TwitterClient;
use App\Utils\HashtagCollector;

class TwitterService {

    private $client;

    public function __construct() {
        $this->client = new TwitterClient(); 
    }

    public function searchTweet($searchString) {
        $searchResults = $this->client->search($searchString);
        $tweets = [ 'tweets' => [], 'meta' => [] ];
        $hashtagCollector = new HashtagCollector();

        foreach($searchResults->statuses as $tweet) {
            $hashtagCollector->getHashtagsFromTweet($tweet);
            array_push($tweets['tweets'], [
                'id' => $tweet->id_str,
                'name' => $tweet->user->name,
                'handle' => $tweet->user->screen_name,
                'profile_picture' => $tweet->user->profile_image_url_https,
                'contents' => $tweet->text
            ]);
        }
        
        $tweets['meta']['top10Hashtags'] = $hashtagCollector->getTop10Hashtags();

        return $tweets;
    }
}