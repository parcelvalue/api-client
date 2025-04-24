<?php

declare(strict_types=1);

namespace ParcelValue\ApiClient;

use WebServCo\Framework\AbstractOutputLoader;
use WebServCo\Framework\Helpers\HtmlOutputLibraryHelper;
use WebServCo\Framework\Helpers\JsonOutputLibraryHelper;

final class OutputLoader extends AbstractOutputLoader
{
    public function __construct(string $projectPath)
    {
        parent::__construct(
            $projectPath,
            HtmlOutputLibraryHelper::library(),
            JsonOutputLibraryHelper::library(),
        );
    }

    public function cli(string $string, bool $eol = true): bool
    {
        return parent::cli($string, $eol);
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<int|string,mixed> $data
    */
    public function html(array $data, string $template): string
    {
        return parent::html($data, $template);
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<int|string,mixed> $data
    */
    public function htmlPage(array $data, string $pageTemplate, ?string $mainTemplate = null): string
    {
        return parent::htmlPage($data, $pageTemplate, $mainTemplate);
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<string,mixed> $data
    */
    public function json(array $data): string
    {
        return parent::json($data);
    }
}
