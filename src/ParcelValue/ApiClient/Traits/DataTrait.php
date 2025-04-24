<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient\Traits;

use WebServCo\Framework\Exceptions\ApplicationException;

use function array_key_exists;
use function is_array;
use function is_scalar;

trait DataTrait
{
    /**
     * @phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @phpcs:disable SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
     * @param array<mixed> $array
     * @return array<mixed>
     */
    protected function getArrayFromArray(array $array, string $key1, ?string $key2): array
    {
        if (!array_key_exists($key1, $array)) {
            throw new ApplicationException('Missing data.');
        }
        if (!is_array($array[$key1])) {
            throw new ApplicationException('Invalid data.');
        }

        if (!is_array($array[$key1])) {
            throw new ApplicationException('Invalid data.');
        }

        if ($key2 === null) {
            return $array[$key1];
        }

        if (!array_key_exists($key2, $array[$key1])) {
            throw new ApplicationException('Missing data.');
        }

        if (!is_array($array[$key1][$key2])) {
            throw new ApplicationException('Invalid data.');
        }

        return $array[$key1][$key2];
    }
    /** @phpcs:enable */

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $array
     */
    protected function getStringFromArray(array $array, string $key1, string $key2): string
    {
        if (!array_key_exists($key1, $array)) {
            throw new ApplicationException('Missing data.');
        }
        if (!is_array($array[$key1])) {
            throw new ApplicationException('Invalid data.');
        }

        if (!is_scalar($array[$key1][$key2])) {
            throw new ApplicationException('Invalid data.');
        }

        return (string) $array[$key1][$key2];
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed> $array
     */
    protected function getStringFromArray3(array $array, string $key1, string $key2, string $key3): string
    {
        $array = $this->getArrayFromArray($array, $key1, null);

        return $this->getStringFromArray($array, $key2, $key3);
    }
}
