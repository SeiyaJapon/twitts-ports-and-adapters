<?php

namespace App\Application\GetTweet\Query;

use App\Application\Query\QueryResultInterface;

class GetTweetQueryResult implements QueryResultInterface
{
    private array $tweets;

    public function __construct(array $tweets)
    {
        $this->tweets = $tweets;
    }

    public function getTweets(): array
    {
        return $this->tweets;
    }

    public function result(): array
    {
        return $this->tweets;
    }
}