<?php
namespace ParcelValue\ApiClient;

abstract class AbstractController extends \WebServCo\Framework\AbstractController
{
    protected $apiUrl;
    protected $apiVersion;
    protected $clientId;
    protected $clientKey;
    protected $serverKey;

    protected $repository;

    use \ParcelValue\ApiClient\Traits\ControllerTrait;

    public function __construct()
    {
        $this->initPaths();

        $outputLoader = new OutputLoader($this->data('path/project'));
        parent::__construct($outputLoader);

        /* custom configuration settings */
        $this->config()->add(
            'app',
            $this->config()->load(
                'App',
                $this->data('path/project')
            )
        );

        $this->apiUrl = $this->config()->get('app/api/url');
        $this->apiVersion = $this->config()->get('app/api/version');
        $this->clientId = $this->config()->get('app/api/clientId');
        $this->clientKey = $this->config()->get('app/api/clientKey');
        $this->serverKey = $this->config()->get('app/api/serverKey');
    }
}
