<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient;

abstract class AbstractRepository extends \WebServCo\Framework\AbstractRepository
{
    public function __construct(\WebServCo\Framework\Interfaces\OutputLoaderInterface $outputLoader)
    {
        parent::__construct($outputLoader);
    }
}
