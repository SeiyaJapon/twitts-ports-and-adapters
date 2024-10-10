<?php

namespace App\Infrastructure\Tweet;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TweetCache
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function get(string $key): ?array
    {
        return $this->cache->get($key, function (ItemInterface $item) {
            return null;
        });
    }

    public function set(string $key, array $tweets): void
    {
        $this->cache->get($key, function (ItemInterface $item) use ($tweets) {
            $item->set($tweets);
            $item->expiresAfter(60);
            return $tweets;
        });
    }

    public function has(string $key): bool
    {
        return $this->cache->hasItem($key);
    }
}