<?php

namespace App\Services;

use App\DTO\Webpage;
use DOMDocument;
use DOMNode;

class ContentParserService
{
    private DOMDocument $dom;

    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
    }

    public function getDom(): DOMDocument
    {
        return $this->dom;
    }

    public function setDom(DOMDocument $dom): void
    {
        $this->dom = $dom;
    }

    /**
     * Helper function to check for an attribute in a \DOMNode object.
     *
     * This is necessary as the \DOMNode object stores the attributes as an indexed array of key => value pairs.
     * @param DOMNode $node The node to scan for an existing attribute,
     * @param string $name THe name of the attribute to be scanned for.
     * @return null|string Either the value of the attribute of the \DOMNode object, or null if it is not found/set.
     */
    private function checkAttribute(DOMNode $node, string $name): ?string
    {
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attribute) {
                if ($attribute->name === $name) {
                    return $attribute->value;
                }
            }
        }

        return null;
    }

    /**
     * Helper function to scan the contents of a request from Laravel's Http class and update a App\Models\Website data model instance with the results.
     * @param Webpage $webpage The Webpage object to update with the analysed results.
     * @param string $content The Http request to analyse the response of.
     * @return Webpage The updated Webpage data model object
     */
    public function parse(Webpage $webpage, string $content): Webpage
    {
        //Prevent throwing exceptions when encountering modern HTML tags
        libxml_use_internal_errors(true);

        $dom = $this->getDom();
        $dom->loadHTML($content);

        $nodes = $dom->getElementsByTagName('*');
        /** @var DOMNode $node */
        foreach ($nodes as $node) {
            $webpage = match ($node->nodeName) {
                'title' => $webpage->setPageTitle($node->textContent),
                'img' => $this->setImages($node, $webpage),
                'svg' => $webpage->addImage(htmlspecialchars($dom->saveHTML($node))),
                'a' => $this->setUrls($node, $webpage),
                default => $this->setWordsCount($node, $webpage)
            };
        }

        return $webpage;
    }

    private function setImages(DOMNode $node, Webpage $webpage): Webpage
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

    public function setUrls(DOMNode $node, Webpage $webpage): Webpage
    {
        $parsedUrl = parse_url($webpage->getPageUrl());

        $url = trim($this->checkAttribute($node, 'href'));

        if (!empty($url)) {
            if ($url[0] === '/') {
                $url = "{$parsedUrl['scheme']}://{$parsedUrl['host']}$url";
                $webpage->addInternalUrl($url);
            } elseif ($url[0] === '#' || parse_url($url, PHP_URL_HOST) === parse_url($webpage->getPageUrl(), PHP_URL_HOST)) {
                $webpage->addInternalUrl($url);
            } else {
                $webpage->addExternalUrl($url);
            }
        }
        return $webpage;
    }

    public function setWordsCount(DOMNode $node, Webpage $webpage): Webpage
    {
        $children = $node->childNodes->length;

        for ($i = 0; $i < $children; $i++) {
            if ($node->childNodes->item($i)->nodeType === 3) {
                $text = $node->childNodes->item($i)->textContent;

                // Strip away common punctuation
                $text = str_replace(array(',', '.', '!', '?', ' / '), '', $text);

                // Compress whitespace to avoid empty "words" being counted
                $text = preg_replace('/\s+/', ' ', $text);

                $webpage->addWordCount(count(array_filter(explode(' ', $text))));
            }
        }

        return $webpage;
    }
}
