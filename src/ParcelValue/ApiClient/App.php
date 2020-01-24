<?php
namespace ParcelValue\ApiClient;

use WebServCo\Framework\Log\FileLogger;

final class App extends \WebServCo\Framework\Application
{
    public function __construct($pathPublic, $pathProject = null)
    {
        /**
         * Project can be located in a completely different place
         * than the web directory.
         */
        $pathProject = $pathProject ?: realpath($pathPublic . '/..');

        parent::__construct($pathPublic, $pathProject, __NAMESPACE__);

        $this->config()->set('app/path/log', sprintf('%svar/log/', $this->projectPath));
    }

    /**
     * Handle HTTP errors.
     */
    protected function haltHttp($errorInfo = [])
    {
        $this->logError($errorInfo, false);
        return parent::haltHttp($errorInfo);
    }

    /**
     * Handle CLI errors
     */
    protected function haltCli($errorInfo = [])
    {
        $this->logError($errorInfo, true);
        return parent::haltCli($errorInfo);
    }

    protected function logError($errorInfo, $isCli = false)
    {
        $logger = new FileLogger(
            sprintf('error%s', $isCli ? 'CLI' : ''),
            $this->config()->get('app/path/log'),
            $this->request()
        );
        $errorMessage = sprintf('Error: %s in %s:%s', $errorInfo['message'], $errorInfo['file'], $errorInfo['line']);
        if ($errorInfo['exception'] instanceof \Exception) {
            $previous = $errorInfo['exception']->getPrevious();
            if ($previous instanceof \Exception) {
                do {
                    $errorMessage .= sprintf(
                        '%sPrevious: %s in %s:%s',
                        PHP_EOL,
                        $previous->getMessage(),
                        $previous->getFile(),
                        $previous->getLine()
                    );
                } while ($previous = $previous->getPrevious());
            }
        }
        $logger->error($errorMessage, $errorInfo);
    }
}
