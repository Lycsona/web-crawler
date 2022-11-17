<?php

namespace App\Services;

use App\DTO\WebPage;
use DOMDocument;
use DOMNode;

class ContentParserService
{
    public function __construct(private readonly DOMDocument $dom)
    {
    }

    public function getDom(): DOMDocument
    {
        return $this->dom;
    }

    /**
     * Parse web page content and transfer the data to a WebPage DTO.
     *
     * @param WebPage $webPage
     * @param string $content
     * @return WebPage
     */
    public function parse(WebPage $webPage, string $content): WebPage
    {
        /** @link https://php.net/manual/en/function.libxml-use-internal-errors.php */
        libxml_use_internal_errors(true);

        $dom = $this->getDom();
        $dom->loadHTML($content);

        $nodes = $dom->getElementsByTagName('*');

        /** @var DOMNode $node */
        foreach ($nodes as $node) {
            $webPage = match ($node->nodeName) {
                'title' => $webPage->setTitle($node->textContent),
                'img' => $this->setImages($node, $webPage),
                'svg' => $webPage->addImage(htmlspecialchars($dom->saveHTML($node))),
                'a' => $this->setUrls($node, $webPage),
                default => $this->setWordsCount($node, $webPage)
            };
        }

        return $webPage;
    }

    private function setImages(DOMNode $node, WebPage $webpage): WebPage
    {
        $image = $this->checkAttribute($node, 'data-src');
        if ($image !== null) {
            $webpage->addImage($image);
        }

        $image = $this->checkAttribute($node, 'src');
        if ($image !== null) {
            $webpage->addImage($image);
        }

        return $webpage;
    }

    /**
     * Filter internal and external links and set internal and external URLs to a WebPage.
     **/
    public function setUrls(DOMNode $node, WebPage $webpage): WebPage
    {
        $parsedUrl = parse_url($webpage->getUrl());

        $url = trim($this->checkAttribute($node, 'href'));

        if (!empty($url)) {
            if ($url[0] === '/') {
                $url = "{$parsedUrl['scheme']}://{$parsedUrl['host']}$url";
                $webpage->addInternalUrl($url);
            } elseif ($url[0] === '#' || parse_url($url, PHP_URL_HOST) === parse_url($webpage->getUrl(), PHP_URL_HOST)) {
                $webpage->addInternalUrl($url);
            } else {
                $webpage->addExternalUrl($url);
            }
        }

        return $webpage;
    }

    /**
     * Filter text, count words and set a word count to a WebPage.
     **/
    public function setWordsCount(DOMNode $node, WebPage $webpage): WebPage
    {
        $children = $node->childNodes->length;

        for ($i = 0; $i < $children; $i++) {
            if ($node->childNodes->item($i)->nodeType === 3) {
                $text = $node->childNodes->item($i)->textContent;

                // Strip common punctuation
                $text = str_replace(array(',', '.', '!', '?', ' / '), '', $text);

                // Compress whitespace
                $text = preg_replace('/\s+/', ' ', $text);

                $webpage->addWordCount(count(array_filter(explode(' ', $text))));
            }
        }

        return $webpage;
    }

    /**
     * Check if attribute exists in a DOMNode object. DOMNode contains a set of attributes as a [key => value].
     *
     * @param DOMNode $node
     * @param string $attributeName
     * @return null|string
     */
    private function checkAttribute(DOMNode $node, string $attributeName): ?string
    {
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attribute) {
                if ($attribute->name === $attributeName) {
                    return $attribute->value;
                }
            }
        }

        return null;
    }
}
