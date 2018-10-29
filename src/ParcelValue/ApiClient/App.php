<?php
namespace ParcelValue\ApiClient;

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
        $logger = new \WebServCo\Framework\FileLogger(
            'error',
            $this->config()->get('app/path/log'),
            $this->request()
        );
        $logger->error(
            sprintf('Error: %s in %s:%s', $errorInfo['message'], $errorInfo['file'], $errorInfo['line']),
            [] /* $errorInfo */
        );
        return parent::haltHttp($errorInfo);
    }

    /**
     * Handle CLI errors
     */
    protected function haltCli($errorInfo = [])
    {
        return parent::haltCli($errorInfo);
    }
}
