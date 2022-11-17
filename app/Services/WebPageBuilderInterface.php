<?php

namespace App\Services;

use App\DTO\WebAnalytics;
use App\DTO\WebPage;
use Illuminate\Http\Client\Response;

interface WebPageBuilderInterface
{
    public function getWebPageContent(string $url): mixed;

    public function skipWebPageDuplicate(string $webPageContent): void;

    public function buildWebPage(string $seedUrl): WebPage;
}
