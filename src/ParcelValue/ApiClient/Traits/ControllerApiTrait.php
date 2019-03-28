<?php
namespace ParcelValue\ApiClient\Traits;

use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Http\Method;

trait ControllerApiTrait
{
    protected $httpResponse;
    protected $requestHeaders;
    protected $responseStatus;
    protected $responseContent;
    protected $responseHeaders;

    abstract protected function config();
    abstract public function data($key, $defaultValue = false);
    abstract protected function request();
    abstract protected function outputCli($string, $eol = true);

    protected function handleApiCall($url, $method, array $headers = [], $requestData = null)
    {
        $this->outputCli('', true);
        $this->outputCli(sprintf('REQUEST: %s %s', $method, $url), true);

        $logger = new \WebServCo\Framework\Log\FileLogger(
            'ParcelValueAPI',
            sprintf('%svar/log/', $this->data('path/project', '')),
            $this->request()
        );

        $apiHelper = new \ParcelValue\Api\Helper($logger, $this->config()->getEnv());
        $this->httpResponse = $apiHelper->getResponse($url, $method, $headers, $requestData);

        $this->requestHeaders = $apiHelper->getRequestHeaders();
        foreach ($this->requestHeaders as $key => $value) {
            $this->outputCli(sprintf('%s: %s', Ansi::sgr($key, [Sgr::BOLD]), $value), true);
        }
        if (Method::POST == $method) {
            $this->outputCli('', true);
            $this->outputCli($requestData, true);
        }

        $this->responseStatus = $this->httpResponse->getStatus();
        $this->responseHeaders = $this->httpResponse->getHeaders();
        $this->responseContent = $this->httpResponse->getContent();

        $this->outputCli('', true);
        $statusCodes = \WebServCo\Framework\Http\StatusCode::getSupported();
        $this->outputCli(
            sprintf(
                'RESPONSE: %s',
                Ansi::sgr(
                    sprintf(
                        '%s %s',
                        $this->responseStatus,
                        $statusCodes[$this->responseStatus] ?: null
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
        return true;
    }

    protected function validateApiConfig()
    {
        foreach ([
            'api/url' => $this->apiUrl,
            'api/version' => $this->apiVersion,
            'clientId' => $this->clientId,
            'clientKey' => $this->clientKey,
            'serverKey' => $this->serverKey,
        ] as $key => $value) {
            if (empty($value)) {
                throw new \InvalidArgumentException(sprintf('Missing or invalid configuration data: %s', $key));
            }
        }
        return true;
    }
}
