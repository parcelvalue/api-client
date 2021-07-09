<?php

declare(strict_types=1);

namespace Tests\ParcelValue\ApiClient;

use ParcelValue\ApiClient\App;
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationWithNullParameterThrowsException(): void
    {
        new App(null);
    }

    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationWithEmptyParameterThrowsException(): void
    {
        new App('');
    }

    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationWithDummyParameterThrowsException(): void
    {
        new App('foo');
    }

    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationInvalidParameterThrowsException(): void
    {
        new App('/tmp', '/tmp');
    }
}
