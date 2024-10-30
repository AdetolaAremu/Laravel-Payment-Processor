<?php

namespace AdetolaAremu\BlinkPayRouter\Contracts;

interface ProcessorInterface
{
  public function processPayment(array $data): array;
  public function supportedCurrency(string $currency): bool;
  public function getReliabilityScore(): int;
  public function getCostPerTransaction(): float;
  public function getName(): string;
}
