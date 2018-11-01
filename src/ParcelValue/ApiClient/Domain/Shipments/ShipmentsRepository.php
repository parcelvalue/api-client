<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

use ParcelValue\Api\JsonApi\ResourceObjects\Shipment;

final class ShipmentsRepository extends \ParcelValue\ApiClient\AbstractRepository
{
    public function getTestShipment()
    {
        $shipment = new Shipment();

        $shipment->setAttribute('shipDate', date(Shipment::DATE_FORMAT, strtotime('tomorrow')));

        $shipment->setAttribute(
            'shipFrom',
            [
                'name' => 'Sender name',
                'address1' => 'Sender street',
                'city' => 'Milano',
                'postalCode' => '20129',
                'state' => 'MI',
                'country' => 'IT',
                'contact' => 'Sender contact name',
                'phone' => '1234567890',
                'email' => 'sender@ship.from',
            ]
        );

        $shipment->setAttribute(
            'shipTo',
            [
                'name' => 'Receiver name',
                'address1' => 'Receiver street',
                'city' => 'Muenchen',
                'postalCode' => '80331',
                'state' => null,
                'country' => 'DE',
                'contact' => 'Receiver contact name',
                'phone' => '0987654321',
                'email' => 'receiver@ship.to'
            ]
        );

        $shipment->setAttribute(
            'packages',
            [
                [
                    'weight' => [
                        'value' => '1.2',
                        'units' => '1'
                    ],
                    'dimensions' => [
                        'length' => '32',
                        'width' => '33',
                        'height' => '34',
                        'units' => '1'
                    ],
                    'packageType' => 'CARTON',
                ],
                [
                    'weight' => [
                        'value' => '1.9',
                        'units' => '1'
                    ],
                    'dimensions' => [
                        'length' => '32',
                        'width' => '33',
                        'height' => '34',
                        'units' => '1'
                    ],
                    'packageType' => 'CARTON',
                ]
            ]
        );

        $shipment->setAttribute('useCod', true);

        $shipment->setAttribute('saturdayDelivery', true);

        return $shipment;
    }
}
