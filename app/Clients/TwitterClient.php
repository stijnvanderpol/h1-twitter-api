<?php

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class TwitterClient {

    private $client;
    private $accessToken;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.twitter.com'
        ]);
    }

    public function search($queryString, $retry = true) {
        $this->authenticateIfUnauthenticated();

        try {
            $response = $this->client->request('GET', '/1.1/search/tweets.json', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken
                ],
                'query' => [
                    'q' => $queryString
                ]
            ]);

            return json_decode($response->getBody());
        } catch (ClientException $e) {

            return $e->getMessage();
            if ($e->getCode() === 401 && $retry) {
                return '3';
                $this->authenticate();
                $this->search($queryString, false);
            }

            return $this->accessToken;;
        }
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
     * @return string
     */
    private function getAuthorizationToken() {
        $key = env('TWITTER_API_KEY');
        $secret = env('TWITTER_API_SECRET');

        return base64_encode($key.':'.$secret);
    }

    private function authenticateIfUnauthenticated() {
        if (!$this->accessToken) {
            $this->authenticate();
        }
    }
}