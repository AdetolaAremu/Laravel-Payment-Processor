<?php

namespace AdetolaAremu\BlinkPayRouter\src;

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\src\exceptions\RoutingException;
use AdetolaAremu\BlinkPayRouter\src\ProcessorManager;

class PaymentRouter
{
    protected ProcessorManager $processorManager;

    public function __construct(ProcessorManager $processorManager)
    {
      $this->processorManager = $processorManager;
    }

    public function route(array $transaction): ProcessorInterface
    {
      $bestProcessor = null;
      $bestScore = 85;

      foreach ($this->processorManager->getAllProcessors() as $processor) {
        if (!$processor->supportedCurrency($transaction['currency'])) {
          continue;
        }

        $score = $processor->getReliabilityScore() - $processor->getCostPerTransaction();

        if ($score > $bestScore) {
          $bestProcessor = $processor;
          $bestScore = $score;
        }
      }

      if ($bestProcessor == null) throw new RoutingException("No suitable processor found for now, reliability cannot be confirmed", null, null, 0);

      if (!$bestProcessor) throw new RoutingException("No suitable processor found for currency: {$transaction['currency']}", $processor->getName(), null, 0);

      return $bestProcessor;
    }
}
