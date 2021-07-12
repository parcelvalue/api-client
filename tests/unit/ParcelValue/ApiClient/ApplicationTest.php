<?php

declare(strict_types=1);

namespace Tests\ParcelValue\ApiClient;

use PHPUnit\Framework\TestCase;
use WebServCo\Framework\Application;

final class ApplicationTest extends TestCase
{
    /**
    * @test
    */
    public function instantiationWithEmptyParametersThrowsException(): Application
    {
        $this->expectException(\WebServCo\Framework\Exceptions\ApplicationException::class);

        return new Application('', '', null);
    }

    /**
    * @test
    */
    public function instantiationWithDummyParametersThrowsException(): Application
    {
        $this->expectException(\WebServCo\Framework\Exceptions\ApplicationException::class);

        return new Application('foo', 'bar', null);
    }

    /**
    * @test
    */
    public function instantiationInvalidParameterThrowsException(): Application
    {
        $this->expectException(\WebServCo\Framework\Exceptions\ApplicationException::class);

        return new Application('/tmp', '/tmp', null);
    }
}
