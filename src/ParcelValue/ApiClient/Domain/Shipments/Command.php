<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
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

        $this->repository = new Repository($this->outputLoader);

        $this->jwt = \ParcelValue\Api\AuthenticationToken::generate(
            Config::int('APP_API_CLIENT_ID'),
            Config::string('APP_API_CLIENT_KEY'),
            Config::int('APP_API_SERVER_KEY'),
        );

        $this->outputLogger = new \WebServCo\Framework\Log\CliOutputLogger();
    }

    public function create(): ResponseInterface
    {
        $this->init();

        $this->outputLogger->debug(Ansi::clear(), true);
        $this->outputLogger->debug(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf('%s%s/shipments', $this->apiUrl, $this->apiVersion);

        $shipment = $this->repository->getShipment();
        $document = new Document();
        $document->setData($shipment);

        $this->handleApiCall($this->jwt, $url, Method::POST, $document->toJson());

        return new Response('', true);
    }

    public function retrieve(string $shipmentId): ResponseInterface
    {
        $this->init();

        $this->outputLogger->debug(Ansi::clear(), true);
        $this->outputLogger->debug(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf('%s%s/shipments/%s', $this->apiUrl, $this->apiVersion, $shipmentId);

        $this->handleApiCall($this->jwt, $url, Method::GET);

        return new Response('', true);
    }

    public function downloadDocuments(string $shipmentId): ResponseInterface
    {
        $this->init();

        $this->outputLogger->debug(Ansi::clear(), true);
        $this->outputLogger->debug(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf('%s%s/shipments/%s/documents', $this->apiUrl, $this->apiVersion, $shipmentId);

        $this->handleApiCall($this->jwt, $url, Method::GET);

        $data = \json_decode($this->responseContent, true);
        if (isset($data['data']['attributes']['fileData']) && isset($data['data']['attributes']['fileName'])) {
            $filePath = \sprintf(
                '%svar/tmp/%s',
                $this->config()->get('app/path/project'),
                $data['data']['attributes']['fileName'],
            );
            try {
                \file_put_contents($filePath, \base64_decode($data['data']['attributes']['fileData'], true));
                $this->outputLogger->debug(
                    Ansi::sgr(\sprintf('Shipment documents saved: %s', $filePath), [Sgr::GREEN]),
                    true,
                );
            } catch (\Throwable $e) {
                $this->outputLogger->debug(
                    Ansi::sgr(\sprintf('Error saving shipment documents: %s', $e->getMessage()), [Sgr::RED]),
                    true,
                );
            }
        }
        return new Response('', true);
    }
}
