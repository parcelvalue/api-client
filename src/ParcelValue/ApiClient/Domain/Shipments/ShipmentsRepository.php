<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use ParcelValue\Api\JsonApi\ResourceObjects\Shipment;
use WebServCo\Framework\Exceptions\ApplicationException;

final class ShipmentsRepository extends \ParcelValue\ApiClient\AbstractRepository
{
    public function getShipment()
    {
        $shipmentConfig = $this->config()->load(
            'Shipment',
            $this->config()->get('path/project')
        );
        $this->verifyShipmentConfig($shipmentConfig);

        $shipment = new Shipment();

        $shipment->setAttribute('shipDate', date(Shipment::DATE_FORMAT, strtotime('tomorrow')));

        $shipment->setAttribute('shipFrom', $shipmentConfig['attributes']['shipFrom']);
        $shipment->setAttribute('shipTo', $shipmentConfig['attributes']['shipTo']);
        $shipment->setAttribute('packages', $shipmentConfig['attributes']['packages']);
        foreach (['useCod', 'saturdayDelivery'] as $item) {
            if (isset($shipmentConfig['attributes'][$item])) {
                $shipment->setAttribute($item, $shipmentConfig['attributes'][$item]);
            }
        }
        $shipment->setService($shipmentConfig['meta']['service']);

        return $shipment;
    }

    protected function verifyShipmentConfig($shipmentConfig)
    {
        if (empty($shipmentConfig) || !is_array($shipmentConfig)) {
            throw new ApplicationException('Missing or invalid shipment configuration');
        }
        foreach (['shipFrom', 'shipTo', 'packages'] as $item) {
            if (!isset($shipmentConfig['attributes'][$item]) || !is_array($shipmentConfig['attributes'][$item])) {
                throw new ApplicationException(
                    sprintf('Missing or invalid shipment configuration attribute: %s', $item)
                );
            }
        }
        if (!isset($shipmentConfig['meta']['service'])) {
            throw new ApplicationException('Missing or invalid shipment configuration meta: service');
        }
        return true;
    }
}
