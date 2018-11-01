<?php
namespace ParcelValue\ApiClient\Domain\Clients;

use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;

final class ClientsCommand extends \ParcelValue\ApiClient\AbstractController
{
    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;
    
    public function __construct()
    {
        parent::__construct();

        $this->repository = new ClientsRepository($this->outputLoader);

        $this->validateApiConfig();
    }

    public function generateAuthenticationToken()
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputCli();

        $jwt = \ParcelValue\Api\AuthenticationToken::generate($this->clientId, $this->clientKey, $this->serverKey);
        $this->outputCli(Ansi::sgr('Success!', [Sgr::GREEN]), false);
        $this->outputCli(' Your token is:', true);
        $this->outputCli();
        $this->outputCli($jwt, true);
        $this->outputCli();
        return new \WebServCo\Framework\CliResponse('', true);
    }
}
