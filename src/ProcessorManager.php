<?php

namespace AdetolaAremu\BlinkPayRouter\src;

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;

class ProcessorManager
{
    protected array $processors = [];

    public function registerProcessor(string $name, ProcessorInterface $processor)
    {
        $this->processors[$name] = $processor;
    }

    public function getProcessor(string $name): ?ProcessorInterface
    {
        return $this->processors[$name] ?? null;
    }

    public function getAllProcessors(): array
    {
        return $this->processors;
    }
}
