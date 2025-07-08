<?php

namespace App\Application\Services;

use League\Tactician\CommandBus as TacticianCommandBus;

class CommandBus
{
    public function __construct(
        private TacticianCommandBus $commandBus
    ) {}

    public function execute(object $command): mixed
    {
        return $this->commandBus->handle($command);
    }
}