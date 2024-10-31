<?php

namespace AdetolaAremu\BlinkPayRouter\Processors;

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\Helpers\Helper;

class MoniepointProcessor implements ProcessorInterface
{
  public function processPayment(array $data): array
  {
    $generateTxnString = Helper::generateTransactionStringForMoniepoint();
    $time = time();

    return ['status' => 'success', 'transaction_id' => $generateTxnString, 'amount' => $data['amount'], 'currency' => $data['currency'], 'time' => $time];
  }

  public function supportedCurrency(string $currency): bool
  {
    $currencies = ['NGN','USD'];

    return in_array($currency, [$currencies]);
  }

  public function getReliabilityScore(): int
  {
    $reliability = Helper::randomReliablity();

    return $reliability;
  }

  public function getCostPerTransaction(): float
  {
    return 1.9;
  }

  public function getName(): string
  {
    return 'Moniepoint';
  }

  public function getPaymentGatewayStatus(): bool
  {
    $getStatus = Helper::randomStatus();

    return $getStatus === 'active' ? true : false;
  }

  public function getLowestAcceptableAmount(): float
  {
    return 100.00;
  }
}