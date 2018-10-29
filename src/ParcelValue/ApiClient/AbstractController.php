<?php
namespace ParcelValue\ApiClient;

abstract class AbstractController extends \WebServCo\Framework\AbstractController
{
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
    }
}
