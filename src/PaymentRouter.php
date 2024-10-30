<?php

namespace AdetolaAremu\BlinkPayRouter;

// use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\exceptions\RoutingException;
use AdetolaAremu\BlinkPayRouter\ProcessorManager;
use AdetolaAremu\BlinkPayRouter\Logger;

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

      $logger = Logger::getLogger();
      $processors = $this->processorManager->getAllProcessors();

      foreach ($processors as $processor) {
        $checkCurrency = $processor->supportedCurrency($transaction['currency']);

        if (!$checkCurrency) continue;

        $score = $processor->getReliabilityScore();

        $logger->info('Inside foreach loop', ['currency' => $checkCurrency]);

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
