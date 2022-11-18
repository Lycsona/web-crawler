<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebCrawlerPostRequest;
use App\Services\WebAnalyticsService;
use App\Services\WebCrawlerService;
use Exception;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebCrawlerController extends Controller
{
    public function __construct(
        private readonly WebCrawlerService   $webCrawlerService,
        private readonly WebAnalyticsService $webAnalyticsService
    )
    {
    }

    /**
     * Run a web crawler.
     *
     * @param WebCrawlerPostRequest $request
     * @return View|RedirectResponse
     * @throws Exception
     */
    public function index(WebCrawlerPostRequest $request): View|RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $webPages = $this->webCrawlerService->crawl($validatedData['url'], $validatedData['depth']);

            $webAnalytics = $this->webAnalyticsService->buildWebPageAnalytics($webPages);

        } catch (RequestException|HttpClientException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return view('result', [
            'webPages' => $webPages,
            'analytics' => $webAnalytics
        ]);
    }
}
