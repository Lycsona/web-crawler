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
    private WebCrawlerService $webCrawlerService;
    private WebAnalyticsService $webAnalyticsService;

    public function __construct(
        WebCrawlerService $webCrawlerService,
        WebAnalyticsService  $webAnalyticsService
    )
    {
        $this->webCrawlerService = $webCrawlerService;
        $this->webAnalyticsService = $webAnalyticsService;
    }

    /**
     * Return crawler result.
     *
     * @return View|RedirectResponse
     */
    public function index(WebCrawlerPostRequest $request):  View|RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $webPages = $this->webCrawlerService->crawl($validatedData['url'], $validatedData['depth']);

            $webAnalytics = $this->webAnalyticsService->buildWebAnalitics($webPages);
        } catch (RequestException|HttpClientException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return view('index', [
            'webPages' => $webPages,
            'analytics' => $webAnalytics
        ]);
    }
}
