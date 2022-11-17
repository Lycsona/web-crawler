<?php

namespace App\DTO;

final class WebAnalytics
{
    public int $numberOfCrawledPages;

    public int $numberOfUniqueImages;

    public int $numberOfUniqueInternalLinks;

    public int $numberOfUniqueExternalLinks;

    public float $averagePageLoad;

    public int $averageWordCount;

    public int $averageTitleLength;

    public function getNumberOfCrawledPages(): int
    {
        return $this->numberOfCrawledPages;
    }

    public function setNumberOfCrawledPages(int $numberOfCrawledPages): void
    {
        $this->numberOfCrawledPages = $numberOfCrawledPages;
    }

    public function getNumberOfUniqueImages(): int
    {
        return $this->numberOfUniqueImages;
    }

    public function setNumberOfUniqueImages(int $numberOfUniqueImages): void
    {
        $this->numberOfUniqueImages = $numberOfUniqueImages;
    }

    public function getNumberOfUniqueInternalLinks(): int
    {
        return $this->numberOfUniqueInternalLinks;
    }

    public function setNumberOfUniqueInternalLinks(int $numberOfUniqueInternalLinks): void
    {
        $this->numberOfUniqueInternalLinks = $numberOfUniqueInternalLinks;
    }

    public function getNumberOfUniqueExternalLinks(): int
    {
        return $this->numberOfUniqueExternalLinks;
    }

    public function setNumberOfUniqueExternalLinks(int $numberOfUniqueExternalLinks): void
    {
        $this->numberOfUniqueExternalLinks = $numberOfUniqueExternalLinks;
    }

    public function getAveragePageLoad(): float
    {
        return $this->averagePageLoad;
    }

    public function setAveragePageLoad(float $averagePageLoad): void
    {
        $this->averagePageLoad = $averagePageLoad;
    }

    public function getAverageWordCount(): int
    {
        return $this->averageWordCount;
    }

    public function setAverageWordCount(int $averageWordCount): void
    {
        $this->averageWordCount = $averageWordCount;
    }

    public function getAverageTitleLength(): int
    {
        return $this->averageTitleLength;
    }

    public function setAverageTitleLength(int $averageTitleLength): void
    {
        $this->averageTitleLength = $averageTitleLength;
    }
}
