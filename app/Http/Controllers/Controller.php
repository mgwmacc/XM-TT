<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchCompanyRequest;
use App\Mail\HistoricalDataDetails;
use App\Services\HistoricalData\HistoricalDataService;
use App\Services\NasdaqListings\NasdaqListingsService;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @var array|NasdaqListingsService
     */
    private readonly NasdaqListingsService $nasdaqListingsService;

    /**
     * @param NasdaqListingsService $nasdaqListingsService
     */
    public function __construct(NasdaqListingsService $nasdaqListingsService)
    {
        $this->nasdaqListingsService = $nasdaqListingsService;
    }

    /**
     * @return View
     */
    public function index()
    {
        return view('pages.index', [
            'availableCompanySymbols' => json_encode($this->nasdaqListingsService->getAvailableCompanySymbols())
        ]);
    }

    /**
     * @param SearchCompanyRequest $request
     * @param HistoricalDataService $historicalDataService
     * @param NasdaqListingsService $nasdaqListingsService
     * @return View
     */
    public function submit(
        SearchCompanyRequest $request,
        HistoricalDataService $historicalDataService,
        NasdaqListingsService $nasdaqListingsService
    ) {
        $historicalData = $historicalDataService->getHistoricalDataPrepared(
            $request->company_symbol,
            $request->start_date,
            $request->end_date
        );

        $chartDataOpenPrices  = $historicalData['chartDataOpenPrices'];
        $chartDataClosePrices = $historicalData['chartDataClosePrices'];
        $chartDataDates       = $historicalData['chartDataDates'];
        $historicalData       = $historicalData['historicalData'];

        $companyName = $nasdaqListingsService->getCompanyNameBySymbol($request->company_symbol);

        if ($companyName) {
            //log Mail driver
            Mail::to($request->email)->send(
                new HistoricalDataDetails(
                    $request->start_date,
                    $request->end_date,
                    $companyName
                )
            );

            // Queues (line below) are to be used for email sending. Though that's out of scope I suppose.
            // Mail::to($request->email)->queue(new HistoricalDataDetails($request->start_date, $request->end_date));

        } else {
            Log::error('Company name could not be found. Email was not sent.');
        }

        return view('pages.index', [
            'availableCompanySymbols' => json_encode($this->nasdaqListingsService->getAvailableCompanySymbols()),
            'historicalData'          => $historicalData,
            'chartDataOpenPrices'     => json_encode($chartDataOpenPrices),
            'chartDataClosePrices'    => json_encode($chartDataClosePrices),
            'chartDataDates'          => json_encode($chartDataDates)
        ]);
    }
}
