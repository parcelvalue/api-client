<?php

declare(strict_types=1);

namespace Tests\ParcelValue\ApiClient;

use PHPUnit\Framework\TestCase;
use WebServCo\Framework\Application;
use WebServCo\Framework\Exceptions\ApplicationException;

final class ApplicationTest extends TestCase
{
    /**
    * @test
    */
    public function instantiationWithEmptyParametersThrowsException(): Application
    {
        $this->expectException(ApplicationException::class);

        return new Application('', '', null);
    }

    /**
    * @test
    */
    public function instantiationWithDummyParametersThrowsException(): Application
    {
        $this->expectException(ApplicationException::class);

        return new Application('foo', 'bar', null);
    }

    /**
    * @test
    */
    public function instantiationInvalidParameterThrowsException(): Application
    {
        $this->expectException(ApplicationException::class);

        return new Application('/tmp', '/tmp', null);
    }
}
