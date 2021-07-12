<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\AuthenticationToken;

use ParcelValue\Api\AuthenticationToken;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Interfaces\ResponseInterface;

final class Command extends \ParcelValue\ApiClient\AbstractController
{
    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;

    protected string $jwt;

    protected \WebServCo\Framework\Interfaces\OutputLoggerInterface $outputLogger;

    public function __construct()
    {
        parent::__construct();

        $this->jwt = \ParcelValue\Api\AuthenticationToken::generate(
            Config::int('APP_API_CLIENT_ID'),
            Config::string('APP_API_CLIENT_KEY'),
            Config::int('APP_API_SERVER_KEY'),
        );

        $this->outputLogger = new \WebServCo\Framework\Log\CliOutputLogger();
    }

    public function generate(): ResponseInterface
    {
        $this->init();

        $this->outputLogger->debug(Ansi::clear(), true);
        $this->outputLogger->debug(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputLogger->debug();

        $jwt = AuthenticationToken::generate($this->clientId, $this->clientKey, $this->serverKey);
        $this->outputLogger->debug(Ansi::sgr('Success!', [Sgr::GREEN]), false);
        $this->outputLogger->debug(' Your token is:', true);
        $this->outputLogger->debug();
        $this->outputLogger->debug($jwt, true);
        $this->outputLogger->debug();
        return new Response('', true);
    }

    public function validate(string $token): ResponseInterface
    {
        $this->init();

        $this->outputLogger->debug(Ansi::clear(), true);
        $this->outputLogger->debug(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputLogger->debug();
        $this->outputLogger->debug(\sprintf('Input: %s', $token), true);
        $this->outputLogger->debug();

        try {
            $result = AuthenticationToken::decode($token, $this->serverKey);
            $this->outputLogger->debug(Ansi::sgr('Success!', [Sgr::GREEN]), true);
            $this->outputLogger->debug(\var_export($result, true), true);
        } catch (\Throwable $e) {
            $this->outputLogger->debug(
                Ansi::sgr(
                    \sprintf('Error: %s', $e->getMessage()),
                    [Sgr::RED],
                ),
                true,
            );
        }

        $this->outputLogger->debug();
        return new Response('', true);
    }
}
