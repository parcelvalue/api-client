<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\Shipments;

use ParcelValue\Api\JsonApi\ResourceObjects\Shipment;
use WebServCo\Framework\Exceptions\ApplicationException;

final class Repository extends \ParcelValue\ApiClient\AbstractRepository
{
    public function getShipment(): Shipment
    {
        $shipmentConfig = $this->config()->load(
            'Shipment',
            \WebServCo\Framework\Environment\Config::string('APP_PATH_PROJECT'),
        );
        $this->verifyShipmentConfig($shipmentConfig);

        $shipment = new Shipment();

        $shipment->setAttribute('shipDate', \date(Shipment::DATE_FORMAT, \strtotime('next tuesday')));

        $shipment->setAttribute('shipFrom', $shipmentConfig['attributes']['shipFrom']);
        $shipment->setAttribute('shipTo', $shipmentConfig['attributes']['shipTo']);
        $shipment->setAttribute('packages', $shipmentConfig['attributes']['packages']);
        $shipment->setAttribute('goodsDescription', $shipmentConfig['attributes']['goodsDescription']);
        $shipment->setAttribute('invoiceSubtotal', $shipmentConfig['attributes']['invoiceSubtotal']);
        foreach (['booking'] as $item) {
            if (!isset($shipmentConfig['attributes'][$item])) {
                continue;
            }
            $shipment->setAttribute($item, $shipmentConfig['attributes'][$item]);
        }
        $shipment->setAttribute('customerReference', $shipmentConfig['attributes']['customerReference']);
        $shipment->setAttribute('specialInstructions', $shipmentConfig['attributes']['specialInstructions']);
        $shipment->setAttribute('confirmationEmail', $shipmentConfig['attributes']['confirmationEmail']);
        $shipment->setService($shipmentConfig['meta']['service']);

        return $shipment;
    }

    /**
    * @param array<mixed> $shipmentConfig
    */
    protected function verifyShipmentConfig(array $shipmentConfig): bool
    {
        if (empty($shipmentConfig) || !\is_array($shipmentConfig)) {
            throw new ApplicationException('Missing or invalid shipment configuration');
        }
        foreach (['shipFrom', 'shipTo', 'packages'] as $item) {
            if (!isset($shipmentConfig['attributes'][$item]) || !\is_array($shipmentConfig['attributes'][$item])) {
                throw new ApplicationException(
                    \sprintf('Missing or invalid shipment configuration attribute: %s', $item),
                );
            }
        }
        if (!isset($shipmentConfig['meta']['service'])) {
            throw new ApplicationException('Missing or invalid shipment configuration meta: service');
        }
        return true;
    }
}
