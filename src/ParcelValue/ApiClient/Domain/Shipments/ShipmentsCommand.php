<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use WebServCo\Framework\CliResponse;

final class ShipmentsCommand extends \ParcelValue\ApiClient\AbstractController
{
    protected $logger;
    protected $curlBrowser;

    public function __construct()
    {
        parent::__construct();

        $this->repository = new ShipmentsRepository($this->outputLoader);

        $this->logger = new \WebServCo\Framework\FileLogger(
            __FUNCTION__,
            sprintf('%svar/log/', $this->data('path/project')),
            $this->request()
        );
        $this->curlBrowser = new \WebServCo\Framework\CurlBrowser($this->logger);
    }

    public function test($clientId, $clientKey, $serverKey)
    {
        var_dump($this->config()->get('app'));

        // XXX move project to github
        //XXX ? move documentation to parcelvalue/api
        //problem: since it's a public app, can't use PV FW

        //$this->outputCli('', true);
        return new CliResponse('', true);
    }
}
