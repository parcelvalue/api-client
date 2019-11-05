<?php
namespace ParcelValue\ApiClient\Traits;

trait ControllerTrait
{
    abstract protected function config();
    abstract protected function request();
    abstract protected function setData($key, $value);

    protected function setupPaths()
    {
        $this->setData('path', $this->config()->get('app/path'));
        $this->setData('url/app', $this->request()->getAppUrl());
        $this->setData('url/lang', $this->request()->getUrl(['lang']));
        $this->setData('url/current', $this->request()->getUrl());
    }

    /**
     * Called (optionally) by each method.
     */
    protected function init()
    {
    }
}
