<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\Clients;

use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Http\Method;
use WebServCo\Framework\Interfaces\ResponseInterface;

final class Command extends \ParcelValue\ApiClient\AbstractController
{
    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;

    protected string $jwt;

    protected \WebServCo\Framework\Interfaces\OutputLoggerInterface $outputLogger;

    public function __construct()
    {
        parent::__construct();

        $this->jwt = \ParcelValue\Api\JWT\Helper::generate(
            Config::string('APP_API_CLIENT_ID'),
            Config::string('APP_API_CLIENT_KEY'),
            Config::string('APP_API_SERVER_KEY'),
        );

        $this->outputLogger = new \WebServCo\Framework\Log\CliOutputLogger();
    }

    public function current(): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf('%s%s/clients/current', Config::string('APP_API_URL'), Config::string('APP_API_VERSION'));

        $this->handleApiCall($this->jwt, $url, Method::GET, '');

        return new Response();
    }
}
