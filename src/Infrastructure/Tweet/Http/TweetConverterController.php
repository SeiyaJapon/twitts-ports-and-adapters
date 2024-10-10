<?php

namespace App\Infrastructure\Tweet\Http;

use App\Application\GetTweet\Query\GetTweetQuery;
use App\Infrastructure\Query\QueryBusInterface;
use App\Infrastructure\Tweet\TweetCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class TweetConverterController extends AbstractController
{
    private const DEFAULT_LIMIT = 10;
    private const MAX_LIMIT = 10;

    private QueryBusInterface $queryBus;
    private TweetCache $tweetCache;

    public function __construct(QueryBusInterface $queryBus, TweetCache $tweetCache)
    {
        $this->queryBus = $queryBus;
        $this->tweetCache = $tweetCache;
    }

    /**
     * @Route("/tweets/{userName}", methods={"GET"})
     */
    public function index(Request $request, string $userName): JsonResponse
    {
        $limit = (int) $request->query->get('limit', self::DEFAULT_LIMIT);

        if ($limit < 1 || $limit > self::MAX_LIMIT) {
            return new JsonResponse(['error' => 'Invalid limit. Must be between 1 and ' . self::MAX_LIMIT], 400);
        }

        $cacheKey = sprintf('tweets_%s_%d', $userName, $limit);

        if ($this->tweetCache->has($cacheKey)) {
            $tweets = $this->tweetCache->get($cacheKey);
        } else {
            $result = $this->queryBus->ask(new GetTweetQuery($userName, $limit));
            $tweets = array_map(static fn($tweet) => strtoupper($tweet->getText()), $result->result());
            $this->tweetCache->set($cacheKey, $tweets);
        }

        return new JsonResponse($tweets);
    }
}
