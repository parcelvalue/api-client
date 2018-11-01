<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\CliResponse;

final class ShipmentsCommand extends \ParcelValue\ApiClient\AbstractController
{
    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new ShipmentsRepository($this->outputLoader);

        $this->validateApiConfig();
    }

    public function create()
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $this->initApiCall();

        $url = sprintf(
            '%s%s/shipments',
            $this->apiUrl,
            $this->apiVersion
        );

        $jwt = \ParcelValue\Api\AuthenticationToken::generate($this->clientId, $this->clientKey, $this->serverKey);
        $headers = [
            'Authorization' => sprintf('Bearer %s', $jwt),
            'Content-Type' => Document::CONTENT_TYPE,
        ];

        $shipment = $this->repository->getTestShipment();

        $document = new Document();
        $document->setData($shipment);

        $this->handleApiCall(
            $url,
            \WebServCo\Framework\Http::METHOD_POST,
            $headers,
            $document->toJson()
        );

        return new CliResponse('', true);
    }
}
