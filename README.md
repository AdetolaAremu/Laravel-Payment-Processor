# BLINQPAY ROUTER

### Requirements

[PHP](https://php.net) 8.x.x, [Laravel](https://laravel.com/) 10.x.x and [Composer](https://getcomposer.org/) are required.

### Installation

Install the package via composer:

```bash
composer require adetolaaremu/blinqpay-router
```

After installating, You can decide to publish the configuration using
Note: This is optional.

```bash
composer vendor:publish -tag=smart-payment-router
```

### Configuration

If you decide to publish, check your config file you should see **SmartPaymentRouter.php**. You should see something like this

```php
return [
  'processors' => [
    'paystack' => [
      'api_key' => env('PAYSTACK_API_KEY'),
      'secret_key' => env('PAYSTACK_SECRET_KEY'),
      'public_key' => env('PAYSTACK_PULIC_KEY'),
    ],
    'flutterwave' => [
      'api_key' => env('FLUTTERWAVE_API_KEY'),
      'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
      'public_key' => env('FLUTTERWAVE_PULIC_KEY')
    ],
    'moniepoint' => [
      'api_key' => env('MONIEPOINT_API_KEY'),
      'secret_key' => env('MONIEPOINT_SECRET_KEY'),
      'public_key' => env('MONIEPOINT_PULIC_KEY')
    ],
  ],
];
```

### Usage

```php
use adetolaaremu/blinqpay-router\ProcessorManager;
use adetolaaremu/blinqpay-router\PaymentRouter;

$processorManager = new ProcessorManager();
$router = new PaymentRouter($processorManager);

$transaction = [
    'amount' => 500,
    'currency' => 'USD',
];

try {
    $processor = $router->route($transaction);
    $response = $processor->processPayment($transaction);

    return $response;
} catch (RoutingException $e) {
    // Handle errors
}
```

### Features

- Dynamic Processor Selection: Selects the optimal processor based on reliability, cost per transaction, and currency support.
- Configurable Processors: The processor is easy to configure.
- Logging: Built-in logging for monitoring routing choices and debugging issues.
- Exception Handling: Throws RoutingException if no suitable processor is available or if transaction criteria (e.g amount) does not meet processor requirements.

### Contributing

- Fork the repository.
- Create a feature branch: git checkout -b feature-name
- Commit your changes: git commit -m 'feature:......'
- Push to the branch: git push origin feature-name
- Submit a pull request.

### License

This package is open-source software licensed under the **MIT license**.
