<?php
namespace ParcelValue\ApiClient\Traits;

use WebServCo\Api\JsonApi\Document;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Http\Method;

trait ControllerApiTrait
{
    protected $logger;
    protected $curlBrowser;

    protected $httpResponse;
    protected $requestHeaders;
    protected $responseStatus;
    protected $responseContent;
    protected $responseHeaders;

    abstract protected function config();
    abstract public function data($key, $defaultValue = false);
    abstract protected function request();
    abstract protected function outputCli($string, $eol = true);

    protected function initApiCall()
    {
        $this->logger = new \WebServCo\Framework\Log\FileLogger(
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

    protected function handleApiCall($url, $method, array $headers = [], $postData = null)
    {
        $this->outputCli('', true);
        $this->outputCli(sprintf('REQUEST: %s %s', $method, $url), true);

        foreach ($headers as $key => $value) {
            $this->curlBrowser->setRequestHeader($key, $value);
        }

        switch ($method) {
            case Method::POST:
                $this->curlBrowser->setRequestContentType(Document::CONTENT_TYPE);
                $this->curlBrowser->setRequestData($postData);
                break;
            case Method::GET:
            case Method::HEAD:
                break;
            default:
                throw new \WebServCo\Framework\Exceptions\NotImplementedException('Functionality not implemented');
                break;
        }
        $this->curlBrowser->setMethod($method);
        $this->httpResponse = $this->curlBrowser->retrieve($url);

        $this->requestHeaders = $this->curlBrowser->getRequestHeaders();
        foreach ($this->requestHeaders as $key => $value) {
            $this->outputCli(sprintf('%s: %s', Ansi::sgr($key, [Sgr::BOLD]), $value), true);
        }
        if (Method::POST == $method) {
            $this->outputCli('', true);
            $this->outputCli($postData, true);
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
