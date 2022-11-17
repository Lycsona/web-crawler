<?php

namespace App\Services;

use App\DTO\WebAnalytics;
use App\DTO\Webpage;

interface WebAnaliticsBuilderInterface
{
    public function calculateNumberOfCrawledPages(): void;

    public function calculateNumberOfUniqueImages(): void;

    public function calculateNumberOfUniqueInternalLinks(): void;

    public function calculateNumberOfUniqueExternalinks(): void;

    public function calculateAveragePageLoad(): void;

    public function calculateAverageWordCount(): void;

    public function calculateAverageTitleLength(): void;

    /**
     * @param Webpage[] $webpages
     */
    public function buildWebAnalitics(array $webpages): WebAnalytics;
}
