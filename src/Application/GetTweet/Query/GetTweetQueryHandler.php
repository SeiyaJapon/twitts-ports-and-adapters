<?php

namespace App\Application\GetTweet\Query;

use App\Domain\Tweet\Service\TweetService;

class GetTweetQueryHandler
{
    private TweetService $tweetService;

    public function __construct(TweetService $tweetService)
    {
        $this->tweetService = $tweetService;
    }

    public function handle(GetTweetQuery $query): GetTweetQueryResult
    {
        $tweets = $this->tweetService->getTweetsByUserName($query->getUsername(), $query->getLimit());
        return new GetTweetQueryResult($tweets);
    }
}