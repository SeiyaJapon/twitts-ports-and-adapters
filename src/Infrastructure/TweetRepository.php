<?php

namespace App\Infrastructure;

interface TweetRepository
{
    public function searchByUserName(string $username, int $limit): array;
}
