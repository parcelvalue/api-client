<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient;

use ParcelValue\ApiClient\Traits\ControllerTrait;
use ParcelValue\ApiClient\Traits\DataTrait;
use ParcelValue\ApiClient\Traits\LoggerTrait;
use Throwable;
use WebServCo\Framework\AbstractController as FrameworkAbstractController;
use WebServCo\Framework\Environment\Config;

abstract class AbstractController extends FrameworkAbstractController
{
    use ControllerTrait;
    use DataTrait;
    use LoggerTrait;

    public function __construct()
    {
        $projectPath = Config::string('APP_PATH_PROJECT');

        // no library code before calling the parent constructor
        $outputLoader = new OutputLoader($projectPath);

        parent::__construct($outputLoader);

        $this->setupPaths();
    }

    protected function logThrowable(string $channel, Throwable $throwable): bool
    {
        $logger = $this->createLogger($channel);

        $logger->error($throwable->getMessage(), ['throwable' => $throwable]);

        return true;
    }
}
