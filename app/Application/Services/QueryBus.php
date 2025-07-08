<?php

namespace App\Application\Services;

use League\Tactician\CommandBus as TacticianCommandBus;

class QueryBus
{
    public function __construct(
        private TacticianCommandBus $queryBus
    ) {}

    public function execute(object $query): mixed
    {
        return $this->queryBus->handle($query);
    }
}