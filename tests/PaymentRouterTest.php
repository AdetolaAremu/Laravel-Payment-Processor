<?php

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\ProcessorManager;
use AdetolaAremu\BlinkPayRouter\PaymentRouter;
use AdetolaAremu\BlinkPayRouter\Processors\FlutterwaveProcessor;
use AdetolaAremu\BlinkPayRouter\Processors\MoniepointProcessor;
use AdetolaAremu\BlinkPayRouter\Processors\PayStackProcessor;
use PHPUnit\Framework\TestCase;

class PaymentRouterTest extends TestCase
{
    public function testRoutingToBestProcessor()
    {
        $processorManager = new ProcessorManager();

        $processorManager->registerProcessor('paystack', new PayStackProcessor());
        $processorManager->registerProcessor('flutterwave', new FlutterwaveProcessor());
        $processorManager->registerProcessor('moniepoint', new MoniepointProcessor());

        $router = new PaymentRouter($processorManager);

        $transaction = ['amount' => 500, 'currency' => 'USD'];
        $processor = $router->route($transaction);

        $this->assertInstanceOf(ProcessorInterface::class, $processor);
    }

    // check if the chosen stack is active or inactive (so some should be inactive - maybe random)

    // check if amount is lower than lowest acceptable amount

    // check if currency is part of the a chosen payment gateway options
}