<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\AuthenticationToken;

use InvalidArgumentException;
use ParcelValue\Api\JWT\Helper;
use ParcelValue\ApiClient\AbstractController;
use ParcelValue\ApiClient\Traits\ControllerApiTrait;
use Throwable;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Interfaces\OutputLoggerInterface;
use WebServCo\Framework\Interfaces\ResponseInterface;
use WebServCo\Framework\Log\CliOutputLogger;

use function sprintf;
use function var_export;

final class Command extends AbstractController
{
    use ControllerApiTrait;

    protected OutputLoggerInterface $outputLogger;

    public function __construct()
    {
        parent::__construct();

        $this->outputLogger = new CliOutputLogger();
    }

    public function generate(): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputLogger->output('');

        $jwt = Helper::generate(
            Config::string('APP_API_CLIENT_ID'),
            Config::string('APP_API_CLIENT_KEY'),
            Config::string('APP_API_SERVER_KEY'),
        );
        $this->outputLogger->output(Ansi::sgr('Success!', [Sgr::GREEN]), false);
        $this->outputLogger->output(' Your token is:', true);
        $this->outputLogger->output('');
        $this->outputLogger->output($jwt, true);
        $this->outputLogger->output('');

        return new Response();
    }

    public function validate(?string $token = null): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputLogger->output('');
        $this->outputLogger->output(sprintf('Input: %s', $token), true);
        $this->outputLogger->output('');

        try {
            if ($token === null) {
                throw new InvalidArgumentException('Token is missing.');
            }
            // \ParcelValue\Api\JWT\Payload
            $result = Helper::decode($token, Config::string('APP_API_SERVER_KEY'));
            $this->outputLogger->output(Ansi::sgr('Success!', [Sgr::GREEN]), true);
            $this->outputLogger->output(var_export($result, true), true);
        } catch (Throwable $e) {
            $this->logThrowable('AuthenticationToken', $e);
            $this->outputLogger->output(Ansi::sgr(sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        $this->outputLogger->output('');

        return new Response();
    }
}
