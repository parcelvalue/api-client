<?php
namespace ParcelValue\ApiClient\Traits;

use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Http;

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
        $this->curlBrowser->setRequestHeader('Accept', \WebServCo\Api\JsonApi\Document::CONTENT_TYPE);
    }

    protected function handleApiCall($url, $method, array $headers = [], $postData = null)
    {
        $this->outputCli('', true);
        $this->outputCli(sprintf('REQUEST: %s %s', $method, $url), true);

        foreach ($headers as $key => $value) {
            $this->curlBrowser->setRequestHeader($key, $value);
        }

        switch ($method) {
            case Http::METHOD_POST:
                $this->curlBrowser->setPostData($postData);
                break;
            case Http::METHOD_GET:
            case Http::METHOD_HEAD:
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
        if (Http::METHOD_POST == $method) {
            $this->outputCli('', true);
            $this->outputCli($postData, true);
        }

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
