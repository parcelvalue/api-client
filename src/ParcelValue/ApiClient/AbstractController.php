<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient;

abstract class AbstractController extends \WebServCo\Framework\AbstractController
{
    use \ParcelValue\ApiClient\Traits\ControllerTrait;

    public function __construct()
    {
        $projectPath = \WebServCo\Framework\Environment\Config::string('APP_PATH_PROJECT');

        // no library code before calling the parent constructor
        $outputLoader = new OutputLoader($projectPath);

        parent::__construct($outputLoader);

        $this->setupPaths();
    }
}
