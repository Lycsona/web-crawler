<?php

namespace App\Services;

use App\DTO\WebPage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WebPageBuilderService implements WebPageBuilderInterface
{
    private array $webPages = [];

    private array $webPagesHashes = [];

    public function __construct(public readonly ContentParserService $contentParserService)
    {
    }

    public function getWebPages(): array
    {
        return $this->webPages;
    }

    public function getWebPagesHashes(): array
    {
        return $this->webPagesHashes;
    }

    public function addWebPage(WebPage $webPage): void
    {
        $this->webPages[] = $webPage;
    }

    public function addWebPageHash(string $webPagesHashes): void
    {
        $this->webPagesHashes[] = $webPagesHashes;
    }

    /**
     * @throws HttpClientException | RequestException | ConnectionException
     */
    public function getWebPageContent(string $url): Response
    {
        try {
            $response = Http::get($url);

            $response->throw();
        } catch (ConnectionException $exception) {
            throw new ConnectionException('Can not load the web page.');
        }

        return $response;
    }

    private function isWebPageDuplicate(string $contentHash): bool
    {
        return in_array($contentHash, $this->getWebPagesHashes());
    }

    /**
     * Build and return WebPage DTO or null if it is a duplicate.
     *
     * @throws RequestException
     * @throws HttpClientException
     */
    public function buildWebPage(string $seedUrl): ?WebPage
    {
        $requestStartTime = microtime(true);

        $webPageResponse = $this->getWebPageContent($seedUrl);

        $loadTime = microtime(true) - $requestStartTime;

        $content = $webPageResponse->body();

        if ($this->isWebPageDuplicate($contentHash = hash('md5', $content))) {
            return null;
        }

        $this->addWebPageHash($contentHash);

        $webPage = new WebPage($seedUrl);
        $webPage->setLoadTime(str($loadTime));
        $webPage->setHttpResponse($webPageResponse->status());

        return $this->contentParserService->parse($webPage, $content);
    }
}
