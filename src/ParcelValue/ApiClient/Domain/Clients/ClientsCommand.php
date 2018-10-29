<?php
namespace ParcelValue\ApiClient\Domain\Clients;

final class ClientsCommand extends \ParcelValue\ApiClient\AbstractController
{
    public function __construct()
    {
        parent::__construct();

        $this->repository = new ClientsRepository($this->outputLoader);
    }

    public function generateAuthenticationToken($clientId, $clientKey, $serverKey)
    {
        $jwt = \ParcelValue\Api\AuthenticationToken::generate($clientId, $clientKey, $serverKey);
        $this->outputCli($jwt, true);
        return new \WebServCo\Framework\CliResponse('', true);
    }
}
