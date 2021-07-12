<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Traits;

use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Http\Method;
use WebServCo\Framework\Interfaces\ResponseInterface;

trait ControllerApiTrait
{
    protected ResponseInterface $httpResponse;
    protected int $responseStatus;
    protected string $responseContent;

    protected \WebServCo\Framework\Interfaces\OutputLoggerInterface $outputLogger;

    /**
     * Returns data if exists, $defaultValue otherwise.
     *
     * @param mixed $defaultValue
     * @return mixed
     */
    abstract public function data(string $key, $defaultValue = false);

    abstract protected function request(): \WebServCo\Framework\Interfaces\RequestInterface;

    /**
    * @param array<string,mixed>|string $requestData
    */
    protected function handleApiCall(string $jwt, string $url, string $method, $requestData): bool
    {
        $this->outputLogger->debug('', true);
        $this->outputLogger->debug(\sprintf('REQUEST: %s %s', $method, $url), true);

        $logger = new \WebServCo\Framework\Log\FileLogger(
            'ParcelValueAPI',
            \sprintf('%svar/log/', $this->data('path/project', '')),
            $this->request(),
        );

        $apiHelper = new \ParcelValue\Api\Helper($logger, $jwt);
        $this->httpResponse = $apiHelper->getResponse($url, $method, $requestData);

        if (Method::POST === $method) {
            $this->outputLogger->debug('', true);
            $this->outputLogger->debug($requestData, true);
        }

        $this->responseStatus = $this->httpResponse->getStatus();
        $this->responseContent = $this->httpResponse->getContent();

        $this->outputLogger->debug('', true);
        $statusCodes = \WebServCo\Framework\Http\StatusCode::getSupported();
        $this->outputLogger->debug(
            \sprintf(
                'RESPONSE: %s',
                Ansi::sgr(
                    \sprintf(
                        '%s %s',
                        $this->responseStatus,
                        $statusCodes[$this->responseStatus] ?? '',
                    ),
                    [400 > $this->responseStatus ? Sgr::GREEN : Sgr::RED],
                ),
            ),
            true,
        );
        $this->outputLogger->debug('', true);
        $this->outputLogger->debug($this->responseContent, true);

        $this->outputLogger->debug('', true);
        $this->outputLogger->debug(
            \sprintf(
                'Processed result: %s',
                \json_encode(\json_decode($this->responseContent, true), \JSON_PRETTY_PRINT),
            ),
            true,
        );
        return true;
    }
}
