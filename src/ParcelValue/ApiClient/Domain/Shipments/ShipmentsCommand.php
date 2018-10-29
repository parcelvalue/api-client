<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Framework\CliResponse;

final class ShipmentsCommand extends \ParcelValue\ApiClient\AbstractController
{
    protected $logger;
    protected $curlBrowser;

    protected $httpResponse;
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
        $this->curlBrowser->setRequestHeader('Accept', \WebServCo\Api\JsonApi\Structure::CONTENT_TYPE);
    }

    public function create($clientId, $clientKey, $serverKey)
    {
        $this->outputCli(__METHOD__, true);

        $url = sprintf(
            '%s%s/shipments',
            $this->config()->get('app/api/url'),
            $this->config()->get('app/api/version')
        );

        $jwt = \ParcelValue\Api\AuthenticationToken::generate($clientId, $clientKey, $serverKey);
        $this->curlBrowser->setRequestHeader('Authorization', sprintf('Bearer %s', $jwt));

        $postData = [];

        $this->outputCli('---', true);
        $this->outputCli('REQUEST', true);
        $this->outputCli(sprintf('POST %s', $url), true);
        $this->outputCli(sprintf('Headers: %s', print_r($this->curlBrowser->getRequestHeaders(), true)), true);
        $this->outputCli(sprintf('POST data: %s', print_r($postData, true)), true);

        $this->httpResponse = $this->curlBrowser->post($url, $postData);
        $this->responseStatus = $this->httpResponse->getStatus();
        $this->responseHeaders = $this->httpResponse->getHeaders();
        $this->responseContent = $this->httpResponse->getContent();

        $this->outputCli('---', true);
        $this->outputCli('RESPONSE', true);
        $this->outputCli(sprintf('Status code: %s', $this->responseStatus), true);
        $this->outputCli(sprintf('Headers: %s', print_r($this->responseHeaders, true)), true);
        $this->outputCli(sprintf('Content: %s', print_r(json_decode($this->responseContent), true)), true);

        return new CliResponse('', true);
    }
}
