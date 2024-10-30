<?php
namespace AdetolaAremu\BlinkPayRouter;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

class Logger
{
    private static $logger;

    public static function getLogger(): MonologLogger
    {
        if (!self::$logger) {
            // Create a log channel
            self::$logger = new MonologLogger('BlinkPayRouter');
            // Rotate log files daily with a maximum of 7 files retained
            self::$logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../logs/blinkpay.log', 7, MonologLogger::DEBUG));
        }

        return self::$logger;
    }
}
