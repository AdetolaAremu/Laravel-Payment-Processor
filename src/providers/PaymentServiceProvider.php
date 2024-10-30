<?php

namespace AdetolaAremu\BlinkPayRouter\src\providers;

use AdetolaAremu\BlinkPayRouter\Processors\FlutterwaveProcessor;
use AdetolaAremu\BlinkPayRouter\Processors\MoniepointProcessor;
use AdetolaAremu\BlinkPayRouter\Processors\PayStackProcessor;
use AdetolaAremu\BlinkPayRouter\src\PaymentRouter;
use AdetolaAremu\BlinkPayRouter\src\ProcessorManager;
use Illuminate\Support\ServiceProvider;

class SmartRouterServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/SmartPaymentRouter.php', 'SmartPaymentRouter');

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
          __DIR__ . '/../config/SmartPaymentRouter.php' => $this->app->make('path.config') . '/SmartPaymentRouter.php',
      ], 'config');
    }
  }
}
