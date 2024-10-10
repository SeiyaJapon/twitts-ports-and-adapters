<?php

declare (strict_types=1);

namespace App\Tests\Unit\Infrastructure\Tweet\Http;

use App\Application\GetTweet\Query\GetTweetQueryResult;
use App\Infrastructure\Tweet\Http\TweetConverterController;
use App\Infrastructure\Query\QueryBusInterface;
use App\Infrastructure\Tweet\TweetCache;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TweetConverterControllerTest extends TestCase
{
    /** @var (QueryBusInterface&object&\PHPUnit\Framework\MockObject\MockObject)|(QueryBusInterface&object&\PHPUnit\Framework\MockObject\MockObject&object&\PHPUnit\Framework\MockObject\MockObject)|(QueryBusInterface&object&\PHPUnit\Framework\MockObject\MockObject&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject */
    private $queryBus;
    /** @var (object&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject&TweetCache)|(object&\PHPUnit\Framework\MockObject\MockObject&TweetCache&object&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject&TweetCache&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject */
    private $tweetCache;
    private TweetConverterController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->queryBus = $this->createMock(QueryBusInterface::class);
        $this->tweetCache = $this->createMock(TweetCache::class);

        $this->controller = new TweetConverterController($this->queryBus, $this->tweetCache);
    }

    public function testIndexReturnsErrorWhenLimitExceeds()
    {
        $request = new Request(['limit' => 11]); // Assuming 100 is the maximum limit

        $response = $this->controller->index($request, 'jackDorsey');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'Limit exceeds the maximum allowed value'], json_decode($response->getContent(), true));
    }

    public function testIndexReturnsTweetsFromCache()
    {
        $request = new Request(['limit' => 10]);

        $this->tweetCache
            ->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $this->tweetCache
            ->expects($this->once())
            ->method('get')
            ->willReturn(['JUST SETTING UP MY TWTTR', 'WE NEED A NEW MOBILE OS THAT’S WEB-ONLY']);

        $response = $this->controller->index($request, 'jackDorsey');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['JUST SETTING UP MY TWTTR', 'WE NEED A NEW MOBILE OS THAT’S WEB-ONLY'], json_decode($response->getContent(), true));
    }

    public function testIndexFetchesTweetsWhenNotInCache()
    {
        $queryBus = $this->createMock(QueryBusInterface::class);
        $tweetCache = $this->createMock(TweetCache::class);
        $controller = new TweetConverterController($queryBus, $tweetCache);

        $tweets = ['Just setting up my twttr', 'We need a new mobile OS that’s web-only'];
        $queryBus->method('ask')->willReturn(new GetTweetQueryResult($tweets));

        $tweetCache->method('has')->willReturn(false);
        $tweetCache->expects($this->once())->method('set');

        $request = new Request(['limit' => 2]);
        $response = $controller->index($request, 'jackDorsey');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['JUST SETTING UP MY TWTTR', 'WE NEED A NEW MOBILE OS THAT’S WEB-ONLY'], json_decode($response->getContent(), true));
    }
}