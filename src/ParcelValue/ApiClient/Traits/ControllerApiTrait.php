<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Traits;

use ParcelValue\Api\Helper;
use WebServCo\Framework\Cli\Ansi;
use WebServCo\Framework\Cli\Sgr;
use WebServCo\Framework\Http\Method;
use WebServCo\Framework\Http\StatusCode;
use WebServCo\Framework\Interfaces\OutputLoggerInterface;
use WebServCo\Framework\Interfaces\RequestInterface;
use WebServCo\Framework\Interfaces\ResponseInterface;

use function json_decode;
use function json_encode;
use function sprintf;
use function var_export;

use const JSON_PRETTY_PRINT;

trait ControllerApiTrait
{
    use LoggerTrait;

    protected ResponseInterface $httpResponse;
    protected int $responseStatus;
    protected string $responseContent;

    protected OutputLoggerInterface $outputLogger;

    /**
     * Returns data if exists, $defaultValue otherwise.
     */
    abstract public function data(string $key, mixed $defaultValue = false): mixed;

    abstract protected function request(): RequestInterface;

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<string,mixed>|string $requestData
    */
    protected function handleApiCall(string $jwt, string $url, string $method, array|string $requestData): bool
    {
        $this->outputLogger->output('', true);
        $this->outputLogger->output(sprintf('REQUEST: %s %s', $method, $url), true);

        $logger = $this->createLogger('ParcelValueAPI');

        $apiHelper = new Helper($logger, $jwt);
        $this->httpResponse = $apiHelper->getResponse($url, $method, $requestData);

        if ($method === Method::POST) {
            $this->outputLogger->output('', true);
            $this->outputLogger->output(var_export($requestData, true), true);
        }

        $this->responseStatus = $this->httpResponse->getStatus();
        $this->responseContent = $this->httpResponse->getContent();

        $this->logResponse();

        $this->outputLogger->output('', true);
        $this->outputLogger->output(
            sprintf(
                'Processed result: %s',
                json_encode(json_decode($this->responseContent, true), JSON_PRETTY_PRINT),
            ),
            true,
        );

        return true;
    }

    private function logResponse(): bool
    {
        $this->outputLogger->output('', true);
        $statusCodes = StatusCode::getSupported();
        $this->outputLogger->output(
            sprintf(
                'RESPONSE: %s',
                Ansi::sgr(
                    sprintf(
                        '%s %s',
                        $this->responseStatus,
                        $statusCodes[$this->responseStatus] ?? '',
                    ),
                    [400 > $this->responseStatus ? Sgr::GREEN : Sgr::RED],
                ),
            ),
            true,
        );
        $this->outputLogger->output('', true);
        $this->outputLogger->output($this->responseContent, true);

        return true;
    }
}
