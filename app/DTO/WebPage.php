<?php

namespace App\DTO;

class WebPage
{
    private int $httpResponse;

    private string $title = '';

    private int $wordCount = 0;

    private string $loadTime;

    private array $images = [];

    private array $internalUrls = [];

    private array $externalUrls = [];

    public function __construct(private readonly string $url)
    {
    }

    public function addImage(string $url): self
    {
        $this->images[] = $url;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function addInternalUrl(string $url): void
    {
        $this->internalUrls[] = $url;
    }

    public function getInternalUrls(): array
    {
        return $this->internalUrls;
    }

    public function addExternalUrl(string $url): void
    {
        $this->externalUrls[] = $url;
    }

    public function getExternalUrls(): array
    {
        return $this->externalUrls;
    }

    public function getLoadTime(): string
    {
        return $this->loadTime;
    }

    public function setLoadTime(string $time): void
    {
        $this->loadTime = $time;
    }

    public function addWordCount(int $words = 1): void
    {
        $this->wordCount += $words;
    }

    public function getWordCount(): int
    {
        return $this->wordCount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getHttpResponse(): int
    {
        return $this->httpResponse;
    }

    public function setHttpResponse(int $code): void
    {
        $this->httpResponse = $code;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
