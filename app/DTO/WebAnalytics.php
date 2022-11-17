<?php

namespace App\DTO;

class WebAnalytics
{
    public int $numberOfCrawledPages;

    public int $numberOfUniqueImages;

    public int $numberOfUniqueInternalLinks;

    public int $numberOfUniqueExternalLinks;

    public float $averagePageLoad;

    public int $averageWordCount;

    public int $averageTitleLength;

    /**
     * @return int
     */
    public function getNumberOfCrawledPages(): int
    {
        return $this->numberOfCrawledPages;
    }

    /**
     * @param int $numberOfCrawledPages
     */
    public function setNumberOfCrawledPages(int $numberOfCrawledPages): void
    {
        $this->numberOfCrawledPages = $numberOfCrawledPages;
    }

    /**
     * @return int
     */
    public function getNumberOfUniqueImages(): int
    {
        return $this->numberOfUniqueImages;
    }

    /**
     * @param int $numberOfUniqueImages
     */
    public function setNumberOfUniqueImages(int $numberOfUniqueImages): void
    {
        $this->numberOfUniqueImages = $numberOfUniqueImages;
    }

    /**
     * @return int
     */
    public function getNumberOfUniqueInternalLinks(): int
    {
        return $this->numberOfUniqueInternalLinks;
    }

    /**
     * @param int $numberOfUniqueInternalLinks
     */
    public function setNumberOfUniqueInternalLinks(int $numberOfUniqueInternalLinks): void
    {
        $this->numberOfUniqueInternalLinks = $numberOfUniqueInternalLinks;
    }

    /**
     * @return int
     */
    public function getNumberOfUniqueExternalLinks(): int
    {
        return $this->numberOfUniqueExternalLinks;
    }

    /**
     * @param int $numberOfUniqueExternalLinks
     */
    public function setNumberOfUniqueExternalLinks(int $numberOfUniqueExternalLinks): void
    {
        $this->numberOfUniqueExternalLinks = $numberOfUniqueExternalLinks;
    }

    /**
     * @return float
     */
    public function getAveragePageLoad(): float
    {
        return $this->averagePageLoad;
    }

    /**
     * @param float $averagePageLoad
     */
    public function setAveragePageLoad(float $averagePageLoad): void
    {
        $this->averagePageLoad = $averagePageLoad;
    }

    /**
     * @return int
     */
    public function getAverageWordCount(): int
    {
        return $this->averageWordCount;
    }

    /**
     * @param int $averageWordCount
     */
    public function setAverageWordCount(int $averageWordCount): void
    {
        $this->averageWordCount = $averageWordCount;
    }

    /**
     * @return int
     */
    public function getAverageTitleLength(): int
    {
        return $this->averageTitleLength;
    }

    /**
     * @param int $averageTitleLength
     */
    public function setAverageTitleLength(int $averageTitleLength): void
    {
        $this->averageTitleLength = $averageTitleLength;
    }
}
