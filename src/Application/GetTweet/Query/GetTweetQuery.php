<?php

declare(strict_types=1);

namespace App\Application\GetTweet\Query;

use App\Application\Query\QueryInterface;

class GetTweetQuery implements QueryInterface
{
    private string $username;
    private int $limit;

    public function __construct(string $username, int $limit)
    {
        $this->username = $username;
        $this->limit = $limit;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}