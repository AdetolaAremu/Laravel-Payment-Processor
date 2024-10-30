<?php

namespace AdetolaAremu\BlinkPayRouter\exceptions;

use Exception;

class RoutingException extends Exception
{
  protected $processor;
  protected $transactionId;

  public function __construct(
    string $message,
    ?string $processor = null,
    ?string $transactionId = null,
    int $code = 0,
    \Throwable $previous = null
  ) {
    $this->processor = $processor;
    $this->transactionId = $transactionId;
    
    parent::__construct($message, $code, $previous);
  }

  public function getProcessor(): ?string
  {
    return $this->processor;
  }

  public function getTransactionId(): ?string
  {
    return $this->transactionId;
  }

  public function __toString(): string
  {
    $baseMessage = parent::__toString();
    $extraInfo = "";

    if ($this->processor) {
      $extraInfo .= " | Processor: " . $this->processor;
    }
    
    if ($this->transactionId) {
      $extraInfo .= " | Transaction ID: " . $this->transactionId;
    }

    return $baseMessage . $extraInfo;
  }
}
