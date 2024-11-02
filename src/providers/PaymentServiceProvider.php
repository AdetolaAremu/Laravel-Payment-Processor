<?php

namespace AdetolaAremu\BlinkPayRouter\providers;

use AdetolaAremu\BlinkPayRouter\Logger;
use AdetolaAremu\BlinkPayRouter\PaymentRouter;
use AdetolaAremu\BlinkPayRouter\ProcessorManager;
use AdetolaAremu\BlinkPayRouter\Processors\FlutterwaveProcessor;
use AdetolaAremu\BlinkPayRouter\Processors\MoniepointProcessor;
use AdetolaAremu\BlinkPayRouter\Processors\PayStackProcessor;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '../../config/SmartPaymentRouter.php', 'SmartPaymentRouter');

    $this->app->singleton(ProcessorManager::class, function () {
      $manager = new ProcessorManager();
      $manager->registerProcessor('paystack', new PayStackProcessor());
      $manager->registerProcessor('flutterwave', new FlutterwaveProcessor());
      $manager->registerProcessor('moniepoint', new MoniepointProcessor());
      return $manager;
    });

    $this->app->singleton(PaymentRouter::class, function ($app) {
      return new PaymentRouter($app->make(ProcessorManager::class));
    });
  }

  public function boot()
  {
    if ($this->app->runningInConsole()) {
      $this->publishes([
          __DIR__ . '/../config/SmartPaymentRouter.php' => $this->app->configPath('SmartPaymentRouter.php'),
      ], 'smart-payment-router');
    }
  }
}
