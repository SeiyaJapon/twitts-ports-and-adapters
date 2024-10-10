<?php

namespace App\Domain\Tweet;

interface TweetRepository
{
    public function searchByUserName(string $username, int $limit): array;
}
