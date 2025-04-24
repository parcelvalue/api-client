<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Traits;

use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Interfaces\FileLoggerInterface;
use WebServCo\Framework\Log\FileLogger;

use function sprintf;

trait LoggerTrait
{
    public function createLogger(string $channel): FileLoggerInterface
    {
        return new FileLogger(
            $channel,
            sprintf('%svar/log/', Config::string('APP_PATH_PROJECT')),
        );
    }
}
