<?php
namespace ParcelValue\ApiClient\Domain\Shipments;

final class ShipmentsRepository extends \ParcelValue\ApiClient\AbstractRepository
{
    public function getTestShipment()
    {
        $shipment = new \ParcelValue\Api\JsonApi\ResourceObjects\Shipment();

        /* */
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
                'email' => 'sender@ship.from'
            ]
        );
        /* */

        /* */
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
        /* */

        return $shipment;
    }
}
