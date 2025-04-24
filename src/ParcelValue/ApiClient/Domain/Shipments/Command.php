<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\Shipments;

use InvalidArgumentException;
use ParcelValue\Api\JWT\Helper;
use ParcelValue\ApiClient\AbstractController;
use ParcelValue\ApiClient\Traits\ControllerApiTrait;
use Throwable;
use UnexpectedValueException;
use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Response;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Http\Method;
use WebServCo\Framework\Interfaces\OutputLoggerInterface;
use WebServCo\Framework\Interfaces\ResponseInterface;
use WebServCo\Framework\Log\CliOutputLogger;

use function base64_decode;
use function file_put_contents;
use function is_array;
use function json_decode;
use function sprintf;

final class Command extends AbstractController
{
    use ControllerApiTrait;

    protected string $jwt;

    protected OutputLoggerInterface $outputLogger;

    protected Repository $repository;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new Repository($this->outputLoader);

        $this->jwt = Helper::generate(
            Config::string('APP_API_CLIENT_ID'),
            Config::string('APP_API_CLIENT_KEY'),
            Config::string('APP_API_SERVER_KEY'),
        );

        $this->outputLogger = new CliOutputLogger();
    }

    public function create(): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf('%s%s/shipments', Config::string('APP_API_URL'), Config::string('APP_API_VERSION'));

        try {
            $shipment = $this->repository->getShipment();
            $document = new Document();
            $document->setData($shipment);

            $this->handleApiCall($this->jwt, $url, Method::POST, $document->toJson());
        } catch (Throwable $e) {
            $this->logThrowable('Shipments', $e);
            $this->outputLogger->output(Ansi::sgr(sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        return new Response();
    }

    public function downloadDocuments(string $shipmentId): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf(
            '%s%s/shipments/%s/documents',
            Config::string('APP_API_URL'),
            Config::string('APP_API_VERSION'),
            $shipmentId,
        );

        try {
            if ($shipmentId === '') {
                throw new InvalidArgumentException('Shipment ID is missing.');
            }

            $this->handleApiCall($this->jwt, $url, Method::GET, '');


            $this->processDocumentDownload();
        } catch (Throwable $e) {
            $this->logThrowable('Shipments', $e);
            $this->outputLogger->output(Ansi::sgr(sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        return new Response();
    }

    public function getTrackingInfo(string $shipmentId): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf(
            '%s%s/shipments/%s/tracking',
            Config::string('APP_API_URL'),
            Config::string('APP_API_VERSION'),
            $shipmentId,
        );

        try {
            if ($shipmentId === '') {
                throw new InvalidArgumentException('Shipment ID is missing.');
            }
            $this->handleApiCall($this->jwt, $url, Method::GET, '');
        } catch (Throwable $e) {
            $this->logThrowable('Shipments', $e);
            $this->outputLogger->output(Ansi::sgr(sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        return new Response();
    }

    public function retrieve(string $shipmentId): ResponseInterface
    {
        $this->init();

        $this->outputLogger->output(Ansi::clear(), true);
        $this->outputLogger->output(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf(
            '%s%s/shipments/%s',
            Config::string('APP_API_URL'),
            Config::string('APP_API_VERSION'),
            $shipmentId,
        );

        try {
            if ($shipmentId === '') {
                throw new InvalidArgumentException('Shipment ID is missing.');
            }
            $this->handleApiCall($this->jwt, $url, Method::GET, '');
        } catch (Throwable $e) {
            $this->logThrowable('Shipments', $e);
            $this->outputLogger->output(Ansi::sgr(sprintf('Error: %s', $e->getMessage()), [Sgr::RED]), true);
        }

        return new Response();
    }

    private function processDocumentDownload(): bool
    {
        $data = json_decode($this->responseContent, true);
        if (!is_array($data)) {
            throw new UnexpectedValueException('Data is not an array.');
        }
        $fileData = $this->getStringFromArray3($data, 'data', 'attributes', 'fileData');
        $fileName = $this->getStringFromArray3($data, 'data', 'attributes', 'fileName');

        $filePath = sprintf('%svar/tmp/%s', Config::string('APP_PATH_PROJECT'), $fileName);
        try {
            file_put_contents($filePath, base64_decode($fileData, true));
            $this->outputLogger->output(
                Ansi::sgr(sprintf('Shipment documents saved: %s', $filePath), [Sgr::GREEN]),
                true,
            );

            return true;
        } catch (Throwable $e) {
            $this->logThrowable('Shipments', $e);
            $this->outputLogger->output(
                Ansi::sgr(sprintf('Error saving shipment documents: %s', $e->getMessage()), [Sgr::RED]),
                true,
            );

            return false;
        }
    }
}
