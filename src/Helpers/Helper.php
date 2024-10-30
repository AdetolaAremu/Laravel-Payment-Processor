<?php

namespace AdetolaAremu\BlinkPayRouter\Helpers;

class Helper
{
  public static function generateTransactionStringForPaystack()
  {
    $prefix = "paystack";
    $randomString = strtoupper(bin2hex(random_bytes(4)));
    $timestamp = time();

    return "{$prefix}_{$randomString}_{$timestamp}";
  }

  public static function generateTransactionStringForFlutterwave()
  {
    $prefix = "flw";
    $randomString = strtoupper(bin2hex(random_bytes(4)));
    $timestamp = time();

    return "{$prefix}_{$randomString}_{$timestamp}";
  }

  public static function generateTransactionStringForMoniepoint()
  {
    $prefix = "moniepoint";
    $randomString = strtoupper(bin2hex(random_bytes(4)));
    $timestamp = time();

    return "{$prefix}_{$randomString}_{$timestamp}";
  }

  public static function randomReliablity()
  {
    $numbers = [90, 95, 80, 70, 75];
    return $numbers[array_rand($numbers)];
  }
}