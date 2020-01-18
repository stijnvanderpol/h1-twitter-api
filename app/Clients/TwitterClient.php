<?php

namespace App\Clients;

use GuzzleHttp\Client;

class TwitterClient {

    private $client; 

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.twitter.com'
        ]);
    }
}