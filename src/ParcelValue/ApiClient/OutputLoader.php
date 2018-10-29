<?php
namespace ParcelValue\ApiClient;

use WebServCo\Framework\Framework;

final class OutputLoader extends \WebServCo\Framework\AbstractOutputLoader
{
    public function __construct($projectPath)
    {
        parent::__construct(
            $projectPath,
            Framework::library('HtmlOutput'),
            Framework::library('JsonOutput')
        );
    }

    public function html($data, $template)
    {
        return parent::html($data, $template);
    }

    public function htmlPage($data, $pageTemplate, $mainTemplate = null)
    {
        return parent::htmlPage($data, $pageTemplate, $mainTemplate);
    }

    public function json($data)
    {
        return parent::json($data);
    }

    public function cli($string, $eol = true)
    {
        return parent::cli($string, $eol);
    }
}
