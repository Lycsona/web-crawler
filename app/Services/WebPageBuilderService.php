<?php

namespace App\Services;

use App\DTO\WebPage;
use App\Exceptions\WebPageDuplicateException;
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

    /**
     * @throws WebPageDuplicateException
     */
    public function skipWebPageDuplicate(string $webPageContent): void
    {
        $contentHash = hash('md5', $webPageContent);

        if (in_array($contentHash, $this->getWebPagesHashes())) {
            throw new WebPageDuplicateException('Web page duplicate was found, the page already crawled.');
        }

        $this->addWebPageHash($contentHash);
    }

    /**
     * @throws WebPageDuplicateException
     * @throws RequestException
     * @throws HttpClientException
     */
    public function buildWebPage(string $seedUrl): WebPage
    {
        $requestStartTime = microtime(true);

        $webPageResponse = $this->getWebPageContent($seedUrl);

        $loadTime = microtime(true) - $requestStartTime;

        $content = $webPageResponse->body();

        $this->skipWebPageDuplicate($content);

        $webPage = new WebPage($seedUrl);
        $webPage->setLoadTime(str($loadTime));
        $webPage->setHttpResponse($webPageResponse->status());

        return $this->contentParserService->parse($webPage, $content);
    }
}
