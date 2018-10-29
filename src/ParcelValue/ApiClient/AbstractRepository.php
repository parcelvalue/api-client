<?php
namespace ParcelValue\ApiClient;

abstract class AbstractRepository extends \WebServCo\Framework\AbstractRepository
{
    public function __construct($outputLoader)
    {
        parent::__construct($outputLoader);
    }
}
