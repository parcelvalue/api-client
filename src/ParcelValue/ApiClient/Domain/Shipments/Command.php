<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Http\Method;
use WebServCo\Framework\Interfaces\ResponseInterface;

final class Command extends \ParcelValue\ApiClient\AbstractController
{
    use \ParcelValue\ApiClient\Traits\ControllerApiTrait;

    protected string $jwt;

    protected \WebServCo\Framework\Interfaces\OutputLoggerInterface $outputLogger;

    protected Repository $repository;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new Repository($this->outputLoader);

        $this->jwt = \ParcelValue\Api\JWT\Helper::generate(
            Config::string('APP_API_CLIENT_ID'),
            Config::string('APP_API_CLIENT_KEY'),
            Config::string('APP_API_SERVER_KEY'),
        );

        $this->outputLogger = new \WebServCo\Framework\Log\CliOutputLogger();
    }

    public function create(): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf('%s%s/shipments', Config::string('APP_API_URL'), Config::string('APP_API_VERSION'));

        $shipment = $this->repository->getShipment();
        $document = new Document();
        $document->setData($shipment);

        $this->handleApiCall($this->jwt, $url, Method::POST, $document->toJson());

        return new Response();
    }

    public function retrieve(?string $shipmentId = null): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf(
            '%s%s/shipments/%s',
            Config::string('APP_API_URL'),
            Config::string('APP_API_VERSION'),
            $shipmentId,
        );

        try {
            if (!$shipmentId) {
                throw new \InvalidArgumentException('Shipment ID is missing.');
            }
            $this->handleApiCall($this->jwt, $url, Method::GET, '');
        } catch (\Throwable $e) {
            $this->outputLogger->output(Ansi::sgr(\sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        return new Response();
    }

    public function downloadDocuments(?string $shipmentId = null): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = \sprintf(
            '%s%s/shipments/%s/documents',
            Config::string('APP_API_URL'),
            Config::string('APP_API_VERSION'),
            $shipmentId,
        );

        try {
            if (!$shipmentId) {
                throw new \InvalidArgumentException('Shipment ID is missing.');
            }

            $this->handleApiCall($this->jwt, $url, Method::GET, '');

            $data = \json_decode($this->responseContent, true);
            if (isset($data['data']['attributes']['fileData']) && isset($data['data']['attributes']['fileName'])) {
                $filePath = \sprintf(
                    '%svar/tmp/%s',
                    $this->config()->get('app/path/project'),
                    $data['data']['attributes']['fileName'],
                );
                try {
                    \file_put_contents($filePath, \base64_decode($data['data']['attributes']['fileData'], true));
                    $this->outputLogger->output(
                        Ansi::sgr(\sprintf('Shipment documents saved: %s', $filePath), [Sgr::GREEN]),
                        true,
                    );
                } catch (\Throwable $e) {
                    $this->outputLogger->output(
                        Ansi::sgr(\sprintf('Error saving shipment documents: %s', $e->getMessage()), [Sgr::RED]),
                        true,
                    );
                }
            }
        } catch (\Throwable $e) {
            $this->outputLogger->output(Ansi::sgr(\sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        return new Response();
    }
}
