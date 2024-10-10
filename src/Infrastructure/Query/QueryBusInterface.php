<?php

declare (strict_types=1);

namespace App\Infrastructure\Query;

use App\Application\Query\QueryInterface;
use App\Application\Query\QueryResultInterface;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): QueryResultInterface;
}