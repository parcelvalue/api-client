<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\CliResponse;
use WebServCo\Framework\Http;

final class ShipmentsCommand extends \ParcelValue\ApiClient\AbstractController
{
    protected $jwt;
    protected $headers;

    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new ShipmentsRepository($this->outputLoader);

        $this->validateApiConfig();

        $this->jwt = \ParcelValue\Api\AuthenticationToken::generate(
            $this->clientId,
            $this->clientKey,
            $this->serverKey
        );
        $this->headers = ['Authorization' => sprintf('Bearer %s', $this->jwt)];
    }

    public function create()
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $this->initApiCall();

        $url = sprintf('%s%s/shipments', $this->apiUrl, $this->apiVersion);

        $shipment = $this->repository->getShipment();
        $document = new Document();
        $document->setData($shipment);

        $this->headers['Content-Type'] = Document::CONTENT_TYPE;

        $this->handleApiCall($url, Http::METHOD_POST, $this->headers, $document->toJson());

        return new CliResponse('', true);
    }

    public function retrieve($shipmentId)
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $this->initApiCall();

        $url = sprintf('%s%s/shipments/%s', $this->apiUrl, $this->apiVersion, $shipmentId);

        $this->handleApiCall($url, Http::METHOD_GET, $this->headers);

        return new CliResponse('', true);
    }

    public function downloadDocuments($shipmentId)
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $this->initApiCall();

        $url = sprintf('%s%s/shipments/%s/documents', $this->apiUrl, $this->apiVersion, $shipmentId);

        $this->handleApiCall($url, Http::METHOD_GET, $this->headers);

        $data = json_decode($this->responseContent, true);
        if (isset($data['data']['attributes']['fileData']) && isset($data['data']['attributes']['fileName'])) {
            $filePath = sprintf(
                '%svar/tmp/%s',
                $this->config()->get('app/path/project'),
                $data['data']['attributes']['fileName']
            );
            try {
                file_put_contents($filePath, base64_decode($data['data']['attributes']['fileData']));
                $this->outputCli(Ansi::sgr(sprintf('Shipment documents saved: %s', $filePath), [Sgr::GREEN]), true);
            } catch (\Exception $e) {
                $this->outputCli(
                    Ansi::sgr(sprintf('Error saving shipment documents: %s', $e->getMessage()), [Sgr::RED]),
                    true
                );
            }
        }
        return new CliResponse('', true);
    }
}
