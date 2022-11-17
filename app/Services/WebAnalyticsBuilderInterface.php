<?php

namespace App\Services;

use App\DTO\WebAnalytics;
use App\DTO\WebPage;

interface WebAnalyticsBuilderInterface
{
    public function calculateNumberOfCrawledPages(): void;

    public function calculateNumberOfUniqueImages(): void;

    public function calculateNumberOfUniqueInternalLinks(): void;

    public function calculateNumberOfUniqueExternalLinks(): void;

    public function calculateAveragePageLoad(): void;

    public function calculateAverageWordCount(): void;

    public function calculateAverageTitleLength(): void;

    /**
     * @param WebPage[] $webpages
     */
    public function buildWebPageAnalytics(array $webpages): WebAnalytics;
}
