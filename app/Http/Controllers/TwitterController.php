<?php

namespace App\Http\Controllers;

use App\Services\TwitterService;
use Illuminate\Http\Request;


class TwitterController extends Controller
{

    private $service;

    public function __construct()
    {
        $this->service = new TwitterService();
    }

    public function search(Request $request) {
        $this->validate($request, [
            'q' => 'required|max:100'
        ]);
        
        $tweets = $this->service->searchTweet($request->input('q'));
        return response()->json($tweets);
    }
}
