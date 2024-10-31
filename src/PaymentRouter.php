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
    $minimumHighestScore = 75;

    $logger = Logger::getLogger();
    $processors = $this->processorManager->getAllProcessors();

    foreach ($processors as $processor) {
      $checkCurrency = $processor->supportedCurrency($transaction['currency']);

      if (!$checkCurrency) continue;

      $serviceIsActive = $processor->getPaymentGatewayStatus();

      if (!$serviceIsActive) continue;
      $logger->info('Active', ['active' => $serviceIsActive]);

      $reliabilityScore = $processor->getReliabilityScore();

      $logger->info('Socre', ['score' => $reliabilityScore]);

      if ($reliabilityScore > $minimumHighestScore) {
        $bestProcessor = $processor;
        $minimumHighestScore = $reliabilityScore;
      }
    }

    if ($bestProcessor == null) throw new RoutingException("No suitable processor found for now, reliability cannot be confirmed", null, null, 0);

    $lowestAmount = $bestProcessor->getLowestAcceptableAmount();

    if ($lowestAmount > $transaction['amount']) throw new RoutingException("The lowest acceptable amount is ".$lowestAmount, null, null, 0);

    return $bestProcessor;
  }
}
