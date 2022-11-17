<?php

namespace App\Services;

use App\DTO\WebAnalytics;
use App\DTO\WebPage;

class WebAnalyticsService implements WebAnalyticsBuilderInterface
{
    /**
     * @param WebPage[] $webpages
     */
    private array $webPages;

    public function __construct(
        private readonly WebAnalytics $webAnalytics,
        array                         $webpages = []
    )
    {
        $this->webPages = $webpages;
    }

    public function getWebAnalytics(): WebAnalytics
    {
        return $this->webAnalytics;
    }

    public function getWebPages(): array
    {
        return $this->webPages;
    }

    public function setWebPages(array $webPages): void
    {
        $this->webPages = $webPages;
    }

    public function calculateNumberOfCrawledPages(): void
    {
        $numberOfCrawledPages = count($this->getWebPages());

        $this->webAnalytics->setNumberOfCrawledPages($numberOfCrawledPages);
    }

    public function calculateNumberOfUniqueImages(): void
    {
        $webpages = $this->getWebPages();

        $webpagesImages = array_map(fn(WebPage $webpage) => $webpage->getImages(), $webpages);

        $numberOfUniqueImages = count(array_unique(array_merge(...$webpagesImages)));

        $this->webAnalytics->setNumberOfUniqueImages($numberOfUniqueImages);
    }

    public function calculateNumberOfUniqueInternalLinks(): void
    {
        $webpages = $this->getWebPages();

        $webpagesInternalLinks = array_map(fn(WebPage $webpage) => $webpage->getInternalUrls(), $webpages);

        $numberOfUniqueInternalLinks = count(array_unique(array_merge(...$webpagesInternalLinks)));

        $this->webAnalytics->setNumberOfUniqueInternalLinks($numberOfUniqueInternalLinks);
    }

    public function calculateNumberOfUniqueExternalLinks(): void
    {
        $webpages = $this->getWebPages();

        $webpagesExternalLinks = array_map(fn(WebPage $webpage) => $webpage->getExternalUrls(), $webpages);

        $numberOfUniqueExternalLinks = count(array_unique(array_merge(...$webpagesExternalLinks)));

        $this->webAnalytics->setNumberOfUniqueExternalLinks($numberOfUniqueExternalLinks);
    }

    public function calculateAveragePageLoad(): void
    {
        $webpages = $this->getWebPages();

        $allWebpages = count($this->webPages);

        $webpagesLoadTime = array_map(fn(WebPage $webpage) => $webpage->getLoadTime(), $webpages);

        $averagePageLoad = round(array_sum($webpagesLoadTime) / $allWebpages, 2);

        $this->webAnalytics->setAveragePageLoad($averagePageLoad);
    }

    public function calculateAverageWordCount(): void
    {
        $webpages = $this->getWebPages();

        $allWebpages = count($webpages);

        $webpagesWordCount = array_map(fn(WebPage $webpage) => $webpage->getWordCount(), $webpages);

        $averageWordCount = round(array_sum($webpagesWordCount) / $allWebpages, 2);

        $this->webAnalytics->setAverageWordCount($averageWordCount);
    }

    public function calculateAverageTitleLength(): void
    {
        $webpages = $this->getWebPages();

        $allWebpages = count($webpages);

        $webpagesTitles = array_map(fn(WebPage $webpage) => $webpage->getTitle(), $webpages);

        $lengthsOfTitles = array_map('strlen', $webpagesTitles);

        $averageTitleLength = array_sum($lengthsOfTitles) / $allWebpages;

        $this->webAnalytics->setAverageTitleLength($averageTitleLength);
    }

    public function buildWebPageAnalytics(array $webpages): WebAnalytics
    {
        $this->setWebPages($webpages);

        $this->calculateNumberOfUniqueImages();
        $this->calculateNumberOfUniqueExternalLinks();
        $this->calculateNumberOfUniqueInternalLinks();
        $this->calculateAveragePageLoad();
        $this->calculateAverageTitleLength();
        $this->calculateAverageWordCount();
        $this->calculateNumberOfCrawledPages();

        return $this->getWebAnalytics();
    }
}
