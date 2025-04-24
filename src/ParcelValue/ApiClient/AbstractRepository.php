<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient;

use ParcelValue\ApiClient\Traits\DataTrait;
use WebServCo\Framework\AbstractRepository as FrameworkAbstractRepository;
use WebServCo\Framework\Interfaces\OutputLoaderInterface;

abstract class AbstractRepository extends FrameworkAbstractRepository
{
    use DataTrait;

    public function __construct(OutputLoaderInterface $outputLoader)
    {
        parent::__construct($outputLoader);
    }
}
