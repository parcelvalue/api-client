<?php
namespace Tests\ApiClient;

use PHPUnit\Framework\TestCase;
use ParcelValue\ApiClient\App;

final class AppTest extends TestCase
{
    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationWithNullParameterThrowsException()
    {
        new App(null);
    }

    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationWithEmptyParameterThrowsException()
    {
        new App('');
    }

    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationWithDummyParameterThrowsException()
    {
        new App('foo');
    }

    /**
    * @test
    * @expectedException \WebServCo\Framework\Exceptions\ApplicationException
    */
    public function instantiationInvalidParameterThrowsException()
    {
        new App('/tmp', '/tmp');
    }
}
