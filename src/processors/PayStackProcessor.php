<?php

namespace AdetolaAremu\BlinkPayRouter\Processors;

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\Helpers\Helper;
use AdetolaAremu\BlinkPayRouter\Logger;

class PayStackProcessor implements ProcessorInterface
{
  // protected string $apiKey;
  // protected string $secretKey;
  // protected string $publicKey;

  // public function __construct(array $config)
  // {
  //   $this->apiKey = $config['api_key'];
  //   $this->secretKey = $config['secret_key'];
  //   $this->publicKey = $config['public_key'];
  // }

  public function processPayment(array $data): array
  {
    $generateTxnString = Helper::generateTransactionStringForPaystack();
    $time = time();

    return ['status' => 'success', 'transaction_id' => $generateTxnString, 'amount' => $data['amount'], 'currency' => $data['currency'], 'time' => $time];
  }

  public function supportedCurrency(string $currency): bool
  {
    $currencies = ['NGN','USD','CAD','EURO','KSH'];

    return in_array($currency, $currencies);
  }

  public function getReliabilityScore(): int
  {
    return 90;
  }

  public function getCostPerTransaction(): float
  {
    return 1.5;
  }

  public function getName(): string
  {
    return 'Paystack';
  }

  public function getPaymentGatewayStatus(): bool
  {
    return true;
  }

  public function getLowestAcceptableAmount(): float
  {
    return 100.00;
  }
}