<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Traits;

trait ControllerTrait
{
    abstract protected function request(): \WebServCo\Framework\Interfaces\RequestInterface;

    /**
     * @param mixed $key Can be an array, a string,
     *                          or a special formatted string
     *                          (eg 'i18n/lang').
     * @param mixed $value The value to be stored.
     * @return bool True on success and false on failure.
     */
    abstract protected function setData($key, $value): bool;

    protected function setupPaths(): void
    {
        $this->setData('url/app', $this->request()->getAppUrl());
        $this->setData('url/lang', $this->request()->getUrl(['lang']));
        $this->setData('url/current', $this->request()->getUrl());
    }

    /**
     * Called (optionally) by each method.
     */
    protected function init(): void
    {
        // No content
    }
}
