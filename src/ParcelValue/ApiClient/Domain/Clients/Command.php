<?php
namespace ParcelValue\ApiClient\Domain\Clients;

use ParcelValue\Api\AuthenticationToken;

use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Http\Method;

final class Command extends \ParcelValue\ApiClient\AbstractController
{
    protected $jwt;

    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new Repository($this->outputLoader);

        $this->validateApiConfig();

        $this->jwt = \ParcelValue\Api\AuthenticationToken::generate(
            $this->clientId,
            $this->clientKey,
            $this->serverKey
        );
    }

    public function current()
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf('%s%s/clients/current', $this->apiUrl, $this->apiVersion);

        $this->handleApiCall($this->jwt, $url, Method::GET);

        return new Response('', true);
    }

    public function generateAuthenticationToken()
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputCli();

        $jwt = AuthenticationToken::generate($this->clientId, $this->clientKey, $this->serverKey);
        $this->outputCli(Ansi::sgr('Success!', [Sgr::GREEN]), false);
        $this->outputCli(' Your token is:', true);
        $this->outputCli();
        $this->outputCli($jwt, true);
        $this->outputCli();
        return new \WebServCo\Framework\Cli\Response('', true);
    }

    public function validateAuthenticationToken($token)
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);
        $this->outputCli();
        $this->outputCli(sprintf('Input: %s', $token), true);
        $this->outputCli();

        try {
            $result = AuthenticationToken::decode($token, $this->serverKey);
            $this->outputCli(Ansi::sgr('Success!', [Sgr::GREEN]), true);
            $this->outputCli(var_export($result, true), true);
        } catch (\Exception $e) {
            $this->outputCli(
                Ansi::sgr(
                    sprintf('Error: %s', $e->getMessage()),
                    [Sgr::RED]
                ),
                true
            );
        }

        $this->outputCli();
        return new \WebServCo\Framework\Cli\Response('', true);
    }
}
