<?php

namespace App\Services;

use App\DTO\WebAnalytics;
use App\DTO\Webpage;

class WebAnalyticsService implements WebAnaliticsBuilderInterface
{
    private WebAnalytics $webAnalytics;

    /**
     * @param Webpage[] $webpages
     */
    private array $webpages;

    public function __construct(
        WebAnalytics $webAnalytics,
        array        $webpages = []
    )
    {
        $this->webAnalytics = $webAnalytics;
        $this->webpages = $webpages;
    }

    public function calculateNumberOfCrawledPages(): void
    {
        $numberOfCrawledPages = count($this->webpages);

        $this->webAnalytics->setNumberOfCrawledPages($numberOfCrawledPages);
    }

    public function calculateNumberOfUniqueImages(): void
    {
        $webpages = $this->webpages;

        $webpagesImages = array_map(fn(Webpage $webpage) => $webpage->getImages(), $webpages);

        $numberOfUniqueImages = count(array_unique(array_merge(...$webpagesImages)));

        $this->webAnalytics->setNumberOfUniqueImages($numberOfUniqueImages);
    }

    public function calculateNumberOfUniqueInternalLinks(): void
    {
        $webpages = $this->webpages;

        $webpagesInternalLinks = array_map(fn(Webpage $webpage) => $webpage->getInternalUrls(), $webpages);

        $numberOfUniqueInternalLinks = count(array_unique(array_merge(...$webpagesInternalLinks)));

        $this->webAnalytics->setNumberOfUniqueInternalLinks($numberOfUniqueInternalLinks);
    }

    public function calculateNumberOfUniqueExternalinks(): void
    {
        $webpages = $this->webpages;

        $webpagesExternalLinks = array_map(fn(Webpage $webpage) => $webpage->getExternalUrls(), $webpages);

        $numberOfUniqueExternalLinks = count(array_unique(array_merge(...$webpagesExternalLinks)));

        $this->webAnalytics->setNumberOfUniqueExternalLinks($numberOfUniqueExternalLinks);
    }

    public function calculateAveragePageLoad(): void
    {
        $webpages = $this->webpages;
        $allWebpages = count($this->webpages);
        $webpagesLoadTime = array_map(fn(Webpage $webpage) => $webpage->getLoadTime(), $webpages);
        $averagePageLoad = round(array_sum($webpagesLoadTime) / $allWebpages, 2);

        $this->webAnalytics->setAveragePageLoad($averagePageLoad);
    }

    public function calculateAverageWordCount(): void
    {
        $webpages = $this->webpages;
        $allWebpages = count($this->webpages);
        $webpagesWordCount = array_map(fn(Webpage $webpage) => $webpage->getWordCount(), $webpages);
        $averageWordCount = round(array_sum($webpagesWordCount) / $allWebpages, 2);

        $this->webAnalytics->setAverageWordCount($averageWordCount);
    }

    public function calculateAverageTitleLength(): void
    {
        $webpages = $this->webpages;
        $allWebpages = count($this->webpages);
        $webpagesTitles = array_map(fn(Webpage $webpage) => $webpage->getPageTitle(), $webpages);
        $lengthsOfTitles = array_map('strlen', $webpagesTitles);
        $averageTitleLength = array_sum($lengthsOfTitles) / $allWebpages;

        $this->webAnalytics->setAverageTitleLength($averageTitleLength);
    }

    public function buildWebAnalitics(array $webpages): WebAnalytics
    {
        $this->webpages = $webpages;

        $this->calculateNumberOfUniqueImages();
        $this->calculateNumberOfUniqueExternalinks();
        $this->calculateNumberOfUniqueInternalLinks();
        $this->calculateAveragePageLoad();
        $this->calculateAverageTitleLength();
        $this->calculateAverageWordCount();
        $this->calculateNumberOfCrawledPages();

        return $this->webAnalytics;
    }
}
