<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\AuthenticationHash;

use ParcelValue\ApiClient\AbstractController;
use ParcelValue\ApiClient\Traits\ControllerApiTrait;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Interfaces\OutputLoggerInterface;
use WebServCo\Framework\Interfaces\ResponseInterface;
use WebServCo\Framework\Log\CliOutputLogger;

use function hash;
use function md5;
use function sprintf;
use function trim;

final class Command extends AbstractController
{
    use ControllerApiTrait;

    protected OutputLoggerInterface $outputLogger;

    public function __construct()
    {
        parent::__construct();

        $this->outputLogger = new CliOutputLogger();
    }

    public function generate(string $username, string $password): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputLogger->output('');

        $hash = hash('sha256', sprintf('%s:%s', trim($username), md5(trim($password))));

        $this->outputLogger->output(Ansi::sgr('Success!', [Sgr::GREEN]), false);
        $this->outputLogger->output(' Your hash is:', true);
        $this->outputLogger->output('');
        $this->outputLogger->output($hash, true);
        $this->outputLogger->output('');

        return new Response();
    }
}
