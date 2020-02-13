<?php
namespace ParcelValue\ApiClient;

final class App extends \WebServCo\Framework\App
{
    /*
    * Needs to be inside the project becasue of the __NAMESPACE__ usage.
    */
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
}
