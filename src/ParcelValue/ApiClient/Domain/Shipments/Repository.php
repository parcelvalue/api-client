<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Domain\Shipments;

use ParcelValue\Api\JsonApi\ResourceObjects\Shipment;
use ParcelValue\ApiClient\AbstractRepository;
use WebServCo\Framework\Environment\Config;
use WebServCo\Framework\Exceptions\ApplicationException;

use function date;
use function is_array;
use function sprintf;
use function strtotime;

final class Repository extends AbstractRepository
{
    public function getShipment(): Shipment
    {
        $shipmentConfig = $this->config()->load(
            'Shipment',
            Config::string('APP_PATH_PROJECT'),
        );

        return $this->createShipment($shipmentConfig);
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
    */
    protected function verifyShipmentConfig(array $shipmentConfig): bool
    {
        if ($shipmentConfig === []) {
            throw new ApplicationException('Missing or invalid shipment configuration');
        }

        $this->verifyShipmentConfigAttributes($shipmentConfig);

        $meta = $this->getArrayFromArray($shipmentConfig, 'meta', null);

        if (!isset($meta['scheduledProcessing'])) {
            throw new ApplicationException('Missing or invalid shipment configuration meta: scheduledProcessing.');
        }
        if (!isset($meta['service'])) {
            throw new ApplicationException('Missing or invalid shipment configuration meta: service.');
        }

        return true;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function createShipment(array $shipmentConfig): Shipment
    {
        $this->verifyShipmentConfig($shipmentConfig);

        $shipment = new Shipment();

        $shipment->setAttribute('shipDate', date(Shipment::DATE_FORMAT, strtotime('next tuesday')));


        $this->setShipmentShipFrom($shipmentConfig, $shipment);

        $this->setShipmentShipTo($shipmentConfig, $shipment);

        $this->setShipmentPackages($shipmentConfig, $shipment);

        $this->setShipmentInsurance($shipmentConfig, $shipment);

        $this->setShipmentGoods($shipmentConfig, $shipment);

        $attributes = $this->getArrayFromArray($shipmentConfig, 'attributes', null);
        foreach (['booking'] as $item) {
            if (!isset($attributes[$item])) {
                continue;
            }
            $shipment->setAttribute($item, $attributes[$item]);
        }

        $this->setShipmentCustomStuff($shipmentConfig, $shipment);

        $shipment->setAttribute('incoTerms', $this->getStringFromArray($shipmentConfig, 'attributes', 'incoTerms'));
        $shipment->setScheduledProcessing(
            (bool) $this->getStringFromArray($shipmentConfig, 'meta', 'scheduledProcessing'),
        );
        $shipment->setService($this->getStringFromArray($shipmentConfig, 'meta', 'service'));

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function setShipmentShipFrom(array $shipmentConfig, Shipment $shipment): Shipment
    {
        $shipment->setAttribute(
            'shipFrom',
            $this->getArrayFromArray($shipmentConfig, 'attributes', 'shipFrom'),
        );

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function setShipmentShipTo(array $shipmentConfig, Shipment $shipment): Shipment
    {
        $shipment->setAttribute(
            'shipTo',
            $this->getArrayFromArray($shipmentConfig, 'attributes', 'shipTo'),
        );

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function setShipmentPackages(array $shipmentConfig, Shipment $shipment): Shipment
    {
        $shipment->setAttribute(
            'packages',
            $this->getArrayFromArray($shipmentConfig, 'attributes', 'packages'),
        );

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function setShipmentInsurance(array $shipmentConfig, Shipment $shipment): Shipment
    {
        $shipment->setAttribute(
            'insuranceDescription',
            $this->getStringFromArray($shipmentConfig, 'attributes', 'insuranceDescription'),
        );

        // float
        $shipment->setAttribute(
            'insuranceValue',
            (float) $this->getStringFromArray($shipmentConfig, 'attributes', 'insuranceValue'),
        );

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function setShipmentGoods(array $shipmentConfig, Shipment $shipment): Shipment
    {
        $shipment->setAttribute(
            'goodsDescription',
            $this->getStringFromArray($shipmentConfig, 'attributes', 'goodsDescription'),
        );

        $shipment->setAttribute(
            'invoiceSubtotal',
            $this->getArrayFromArray($shipmentConfig, 'attributes', 'invoiceSubtotal'),
        );

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function setShipmentCustomStuff(array $shipmentConfig, Shipment $shipment): Shipment
    {
        $shipment->setAttribute(
            'customerReference',
            $this->getStringFromArray($shipmentConfig, 'attributes', 'customerReference'),
        );
        $shipment->setAttribute(
            'specialInstructions',
            $this->getStringFromArray($shipmentConfig, 'attributes', 'specialInstructions'),
        );
        $shipment->setAttribute(
            'confirmationEmail',
            $this->getStringFromArray($shipmentConfig, 'attributes', 'confirmationEmail'),
        );

        return $shipment;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $shipmentConfig
     */
    private function verifyShipmentConfigAttributes(array $shipmentConfig): bool
    {
        $attributes = $this->getArrayFromArray($shipmentConfig, 'attributes', null);

        foreach (['shipFrom', 'shipTo', 'packages'] as $item) {
            if (!isset($attributes[$item]) || !is_array($attributes[$item])) {
                throw new ApplicationException(
                    sprintf('Missing or invalid shipment configuration attribute: %s', $item),
                );
            }
        }

        return true;
    }
}
