<?php

namespace App\Services;

use App\DTO\Webpage;
use App\Exceptions\DuplicateException;
use Exception;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use PharIo\Manifest\InvalidUrlException;

class WebCrawlerService
{
    public function __construct(
        public readonly UrlFrontierService       $urlFrontierService,
        public readonly WebPageDownloaderService $pageDownloaderService
    )
    {
    }

    /**
     * The entry point for performing a "crawl" and analysis of one or more URLs.
     * @param string $seedUrl The initial URL to scan and analyse
     * @param int $depth The maximum number of additional pages to crawl, based on links contained in $seedUrl that refer to the same host.
     * @return Webpage[]
     * @throws Exception
     */
    public function crawl(string $seedUrl, int $depth): array
    {
        try {

            while ($depth > 0) {
                $newWebPagesUrls = $this->urlFrontierService->getNewUrls();
                if (empty($newWebPagesUrls)) {
                    $webPageDTO = $this->pageDownloaderService->generateWebPageDTO($seedUrl);

                    $this->addNewPage($webPageDTO);
                }

                $urls = count($newWebPagesUrls);
                for ($i = 0; $i < $urls; $i++) {

                    if ($this->urlFrontierService->isUrlDuplicate($url = $newWebPagesUrls[$i])) {
                        continue; // Skip and try another url
                    }

                    if (!$this->isValidUrl($url)) {
                        continue; // Skip and try another url
                    }

                    try {
                        $webPageDTO = $this->pageDownloaderService->generateWebPageDTO($url);
                    } catch (DuplicateException|RequestException|HttpClientException) {
                        continue; // Skip and try another url
                    }

                    $this->addNewPage($webPageDTO);

                    self::crawl($seedUrl, --$depth);
                    break 2;
                }
            }

            return $this->pageDownloaderService->getWebPages();
        } catch (Exception $exception) {
            throw new Exception('An error occurs while crawling.', $exception->getCode());
        }
    }

    private function addNewPage(Webpage $webPageDTO): void
    {
        $this->urlFrontierService->addNewUrls($webPageDTO->getInternalUrls());
        $this->urlFrontierService->addCrawledUrl($webPageDTO->getPageUrl());
        $this->pageDownloaderService->addWebPage($webPageDTO);
    }

    private function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false || $url[0] !== '#';
    }
}
