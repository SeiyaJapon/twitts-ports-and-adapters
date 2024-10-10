<?php

namespace App\Infrastructure\Query;

use App\Application\Query\QueryResultInterface;
use League\Tactician\CommandBus;

class TacticianQueryBus implements QueryBusInterface
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function ask($query): QueryResultInterface
    {
        $result = $this->commandBus->handle($query);

        if (!$result instanceof QueryResultInterface) {
            throw new \RuntimeException('Query handler must return an instance of QueryResultInterface.');
        }

        return $result;
    }
}