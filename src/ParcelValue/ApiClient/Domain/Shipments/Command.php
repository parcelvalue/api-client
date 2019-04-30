<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Api\JsonApi\Document;
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

    public function create()
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf('%s%s/shipments', $this->apiUrl, $this->apiVersion);

        $shipment = $this->repository->getShipment();
        $document = new Document();
        $document->setData($shipment);

        $this->handleApiCall($this->jwt, $url, Method::POST, $document->toJson());

        return new Response('', true);
    }

    public function retrieve($shipmentId)
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf('%s%s/shipments/%s', $this->apiUrl, $this->apiVersion, $shipmentId);

        $this->handleApiCall($this->jwt, $url, Method::GET);

        return new Response('', true);
    }

    public function downloadDocuments($shipmentId)
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf('%s%s/shipments/%s/documents', $this->apiUrl, $this->apiVersion, $shipmentId);

        $this->handleApiCall($this->jwt, $url, Method::GET);

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
        return new Response('', true);
    }
}
