<?php

namespace App\Clients;

use GuzzleHttp\Client;

class TwitterClient {

    private $client;
    private $accessToken;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.twitter.com'
        ]);
    }

    /**
     * Authenticates the client to the Twitter API and sets the access token in the accessToken field.
     *
     * @return void
     */
    private function authenticate() {
        $response = $this->client->request('POST', '/oauth2/token', [
            'headers' => [
                'Authorization' => 'Basic ' . $this->getAuthorizationToken()
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $decodedBody = json_decode($response->getBody());

        $this->accessToken = $decodedBody->access_token;
    }

    /**
     * Returns the base64 Twitter Authorization token.
     *
     * @return void
     */
    private function getAuthorizationToken() {
        $key = env('TWITTER_API_KEY');
        $secret = env('TWITTER_API_SECRET');

        return base64_encode($key.':'.$secret);
    }
}