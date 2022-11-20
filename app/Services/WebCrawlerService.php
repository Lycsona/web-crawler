<?php

namespace App\Services;

use App\DTO\WebPage;
use App\Exceptions\WebPageDuplicateException;
use Exception;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;

class WebCrawlerService
{
    public function __construct(
        public readonly UrlFrontierService    $urlFrontierService,
        public readonly WebPageBuilderService $webPageBuilderService
    )
    {
    }

    /**
     * Start web crawling.
     *
     * @param string $seedUrl
     * @param int $depth
     * @return WebPage[]
     * @throws Exception
     */
    public function crawl(string $seedUrl, int $depth): array
    {
        try {
            while ($depth > 0) {

                $newUrlsToCrawl = $this->urlFrontierService->getNewUrls();
                if (empty($newUrlsToCrawl)) {
                    $webPageDTO = $this->webPageBuilderService->buildWebPage($seedUrl);
                    $this->addNewCrawledWebPage($webPageDTO);
                }

                $urlsCount = count($newUrlsToCrawl);
                for ($i = 0; $i < $urlsCount; $i++) {

                    if ($this->urlFrontierService->isUrlDuplicate($url = $newUrlsToCrawl[$i])) {
                        continue; // Skip and try another url
                    }

                    if (!$this->isValidUrl($url)) {
                        continue; // Skip and try another url
                    }

                    try {
                        $webPageDTO = $this->webPageBuilderService->buildWebPage($url);
                    } catch (WebPageDuplicateException|RequestException|HttpClientException) {
                        continue; // Skip and try another url
                    }

                    $this->addNewCrawledWebPage($webPageDTO);

                    $this->crawl($seedUrl, --$depth);
                    break 2;
                }
            }

            return $this->webPageBuilderService->getWebPages();
        } catch (Exception $exception) {
            throw new Exception('An error occurs while crawling.', $exception->getCode());
        }
    }

    private function addNewCrawledWebPage(WebPage $webPageDTO): void
    {
        $this->urlFrontierService->addNewUrls($webPageDTO->getInternalUrls());
        $this->urlFrontierService->addCrawledUrl($webPageDTO->getUrl());
        $this->webPageBuilderService->addWebPage($webPageDTO);
    }

    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false && $url[0] !== '#';
    }
}
