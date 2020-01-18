<?php

namespace App\Utils;

class HashtagCollector {
    private $hashtags = [];

    public function getHashtagsFromTweet($tweet) {
        
        foreach($tweet->entities->hashtags as $hashtag) {
            $this->addOrIncreaseHashtag($hashtag->text);
        }
    }

    public function getHashtags() {
        return $this->hashtags;
    }

    public function getTop10Hashtags() {
        arsort($this->hashtags);
        return array_slice($this->hashtags, 0, 10);
    }

    private function addOrIncreaseHashtag($hashtag) {
        if (array_key_exists($hashtag, $this->hashtags)) {
            $this->hashtags[$hashtag] += 1;
        } else {
            $this->hashtags[$hashtag] = 1;
        }
    }
}