<?php

namespace App\Services;

class UrlFrontierService
{
    private array $newUrls = [];
    private array $crawledUrls = [];

    public function getNewUrls(): array
    {
        return $this->newUrls;
    }

    public function addNewUrls(array $newUrls): array
    {
        $this->newUrls = array_merge($this->newUrls, $newUrls);

        return $this->newUrls;
    }

    public function getCrawledUrls(): array
    {
        return $this->crawledUrls;
    }

    public function addCrawledUrl(string $crawledUrl): array
    {
        $this->crawledUrls[] = $crawledUrl;

        return $this->crawledUrls;
    }

    public function isUrlDuplicate(string $newWebPagesUrl): bool
    {
        return in_array($newWebPagesUrl, $this->getCrawledUrls());
    }
}
