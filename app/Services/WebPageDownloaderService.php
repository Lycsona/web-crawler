<?php

namespace App\Services;

use App\DTO\Webpage;
use App\Exceptions\DuplicateException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use PharIo\Manifest\InvalidUrlException;

class WebPageDownloaderService
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

    public function addWebPage(Webpage $webPage): void
    {
        $this->webPages[] = $webPage;
    }

    public function addWebPageHash(string $webPagesHashes): void
    {
        $this->webPagesHashes[] = $webPagesHashes;
    }

    /**
     * @throws HttpClientException | RequestException
     */
    private function getWebPageContent(string $seedUrl): Response
    {
        try {
            $response = Http::get($seedUrl);

            $response->throw();
        } catch (ConnectionException $exception) {
            throw new ConnectionException('Can not load the web page.');
        }

        return $response;
    }

    /**
     * @throws DuplicateException
     */
    private function skipPageDuplicate(string $webPageContent): void
    {
        $contentHash = hash('md5', $webPageContent);

        if (in_array($contentHash, $this->getWebPagesHashes())) {
            throw new DuplicateException('Web page duplicate was found.');
        }

        $this->addWebPageHash($contentHash);
    }

    /**
     * @throws DuplicateException
     * @throws RequestException
     * @throws HttpClientException
     */
    public function generateWebPageDTO(string $seedUrl): Webpage
    {
        $requestStartTime = microtime(true);

        $webPageResponse = $this->getWebPageContent($seedUrl);

        $loadTime = microtime(true) - $requestStartTime;

        $content = $webPageResponse->body();
        $this->skipPageDuplicate($content);

        $webPage = new Webpage($seedUrl);
        $webPage->setLoadTime(str($loadTime));
        $webPage->setHttpResponse($webPageResponse->status());

        return $this->contentParserService->parse($webPage, $content);
    }
}
