<?php

namespace AdetolaAremu\BlinkPayRouter\Processors;

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\Helpers\Helper;

class PayStackProcessor implements ProcessorInterface
{
  public function processPayment(array $data): array
  {
    $generateTxnString = Helper::generateTransactionStringForPaystack();
    $time = time();

    return ['status' => 'success', 'transaction_id' => $generateTxnString, 'amount' => $data['amount'], 'currency' => $data['currency'], 'time' => $time];
  }

  public function supportedCurrency(string $currency): bool
  {
    $currencies = ['NGN','USD','CAD','EURO','KSH'];

    return in_array($currency, [$currencies]);
  }

  public function getReliabilityScore(): int
  {
    $reliability = Helper::randomReliablity();

    return $reliability;
  }

  public function getCostPerTransaction(): float
  {
    return 1.5;
  }

  public function getName(): string
  {
    return 'Paystack';
  }
}