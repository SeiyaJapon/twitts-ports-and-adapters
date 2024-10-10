<?php

namespace App\Domain\Tweet\Service;

use App\Domain\Tweet\TweetRepository;

class TweetService
{
    private TweetRepository $tweetRepository;

    public function __construct(TweetRepository $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }

    public function getTweetsByUserName(string $username, int $limit): array
    {
        return $this->tweetRepository->searchByUserName($username, $limit);
    }
}