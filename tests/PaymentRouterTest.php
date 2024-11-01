<?php

use AdetolaAremu\BlinkPayRouter\Contracts\ProcessorInterface;
use AdetolaAremu\BlinkPayRouter\exceptions\RoutingException;
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

        $transaction = ['amount' => 500, 'currency' => 'NGN'];
        $processor = $router->route($transaction);

        $this->assertInstanceOf(ProcessorInterface::class, $processor);
    }

    public function testSkipsInactiveProcessor()
    {
        $processorManager = new ProcessorManager();

        /** @var ProcessorInterface|\PHPUnit\Framework\MockObject\MockObject $inactiveHighScoreProcessor */
        $inactiveHighScoreProcessor = $this->createMock(ProcessorInterface::class);
        $inactiveHighScoreProcessor->method('supportedCurrency')->willReturn(true);
        $inactiveHighScoreProcessor->method('getPaymentGatewayStatus')->willReturn(false); // Inactive status
        $inactiveHighScoreProcessor->method('getReliabilityScore')->willReturn(95);
        $inactiveHighScoreProcessor->method('getCostPerTransaction')->willReturn(2.00);

        /** @var ProcessorInterface|\PHPUnit\Framework\MockObject\MockObject $activeLowScoreProcessor */
        $activeLowScoreProcessor = $this->createMock(ProcessorInterface::class);
        $activeLowScoreProcessor->method('supportedCurrency')->willReturn(true);
        $activeLowScoreProcessor->method('getPaymentGatewayStatus')->willReturn(true); // Active status
        $activeLowScoreProcessor->method('getReliabilityScore')->willReturn(80);
        $activeLowScoreProcessor->method('getCostPerTransaction')->willReturn(1.00);

        $processorManager->registerProcessor('inactiveHighScore', $inactiveHighScoreProcessor);
        $processorManager->registerProcessor('activeLowScore', $activeLowScoreProcessor);

        $router = new PaymentRouter($processorManager);

        $transaction = ['currency' => 'USD', 'amount' => 500];
        $processor = $router->route($transaction);

        $this->assertSame($activeLowScoreProcessor, $processor);
    }

    public function testThrowsExceptionWhenNoSuitableProcessorFound()
    {
        $this->expectException(RoutingException::class);
        $this->expectExceptionMessage("No suitable processor found");

        $processorManager = new ProcessorManager();

        /** @var ProcessorInterface|\PHPUnit\Framework\MockObject\MockObject $unsupportedCurrencyProcessor */
        $unsupportedCurrencyProcessor = $this->createMock(ProcessorInterface::class);
        $unsupportedCurrencyProcessor->method('supportedCurrency')->willReturn(false); // Unsupported currency
        $unsupportedCurrencyProcessor->method('getPaymentGatewayStatus')->willReturn(true);
        $unsupportedCurrencyProcessor->method('getReliabilityScore')->willReturn(90);
        $unsupportedCurrencyProcessor->method('getCostPerTransaction')->willReturn(2.00);

        $processorManager->registerProcessor('unsupportedCurrency', $unsupportedCurrencyProcessor);

        $router = new PaymentRouter($processorManager);

        $transaction = ['currency' => 'EUR', 'amount' => 500];
        $router->route($transaction);
    }

    // check if amount is lower than lowest acceptable amount
    public function testLowestAcceptableAmount()
    {
        $this->expectException(RoutingException::class);
        $this->expectExceptionMessage("The lowest acceptable amount is");

        $processorManager = new ProcessorManager();

        /** @var ProcessorInterface|\PHPUnit\Framework\MockObject\MockObject $checkLowestAcceptableAmount */
        $checkLowestAcceptableAmount = $this->createMock(ProcessorInterface::class);
        $checkLowestAcceptableAmount->method('getLowestAcceptableAmount')->willReturn(100.00);
        $checkLowestAcceptableAmount->method('supportedCurrency')->willReturn(true);
        $checkLowestAcceptableAmount->method('getPaymentGatewayStatus')->willReturn(true);
        $checkLowestAcceptableAmount->method('getReliabilityScore')->willReturn(90);
        $checkLowestAcceptableAmount->method('getCostPerTransaction')->willReturn(2.00);
        $processorManager->registerProcessor('lowAmountProcessor', $checkLowestAcceptableAmount);

        $router = new PaymentRouter($processorManager);

        $transaction = ['currency' => 'NGN', 'amount' => 50];

        $router->route($transaction);
    }
}