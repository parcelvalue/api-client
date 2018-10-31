<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\CliResponse;
use WebServCo\Framework\Http;

final class ShipmentsCommand extends \ParcelValue\ApiClient\AbstractController
{
    protected $logger;
    protected $curlBrowser;

    protected $httpResponse;
    protected $requestHeaders;
    protected $responseStatus;
    protected $responseContent;
    protected $responseHeaders;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new ShipmentsRepository($this->outputLoader);

        $this->logger = new \WebServCo\Framework\FileLogger(
            __FUNCTION__,
            sprintf('%svar/log/', $this->data('path/project', '')),
            $this->request()
        );
        $this->curlBrowser = new \WebServCo\Framework\CurlBrowser($this->logger);
        if (\WebServCo\Framework\Environment::ENV_DEV == $this->config()->getEnv()) {
            $this->curlBrowser->setSkipSSlVerification(true);
        }
        $this->curlBrowser->setRequestHeader('Accept', Document::CONTENT_TYPE);
    }

    public function create($clientId, $clientKey, $serverKey)
    {
        $this->outputCli(Ansi::clear(), true);
        $this->outputCli(Ansi::sgr(__METHOD__, [Sgr::BOLD]), true);

        $url = sprintf(
            '%s%s/shipments',
            $this->config()->get('app/api/url'),
            $this->config()->get('app/api/version')
        );

        $jwt = \ParcelValue\Api\AuthenticationToken::generate($clientId, $clientKey, $serverKey);
        $this->curlBrowser->setRequestHeader('Authorization', sprintf('Bearer %s', $jwt));
        $this->curlBrowser->setRequestHeader('Content-Type', Document::CONTENT_TYPE);

        $shipment = $this->repository->getTestShipment();

        $document = new Document();
        $document->setData($shipment);
        $postData = $document->toJson();

        $this->outputCli('', true);
        $this->outputCli(sprintf('REQUEST: POST %s', $url), true);
        $this->httpResponse = $this->curlBrowser->post($url, $postData);
        $this->requestHeaders = $this->curlBrowser->getRequestHeaders();
        foreach ($this->requestHeaders as $key => $value) {
            $this->outputCli(sprintf('%s: %s', Ansi::sgr($key, [Sgr::BOLD]), $value), true);
        }
        $this->outputCli('', true);
        $this->outputCli($postData, true);

        $this->responseStatus = $this->httpResponse->getStatus();
        $this->responseHeaders = $this->httpResponse->getHeaders();
        $this->responseContent = $this->httpResponse->getContent();

        $this->outputCli('', true);
        $this->outputCli(
            sprintf(
                'RESPONSE: %s',
                Ansi::sgr(
                    sprintf(
                        '%s %s',
                        $this->responseStatus,
                        Http::$statusCodes[$this->responseStatus] ?: null
                    ),
                    [400 > $this->responseStatus ? Sgr::GREEN : sgr::RED]
                )
            ),
            true
        );
        foreach ($this->responseHeaders as $key => $value) {
            $this->outputCli(sprintf('%s: %s', Ansi::sgr($key, [Sgr::BOLD]), $value), true);
        }
        $this->outputCli('', true);
        $this->outputCli($this->responseContent, true);

        $this->outputCli('', true);
        $this->outputCli(
            sprintf('Processed result: %s', json_encode(json_decode($this->responseContent, true), JSON_PRETTY_PRINT)),
            true
        );

        return new CliResponse('', true);
    }
}
