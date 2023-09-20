<?php

namespace Tests\Feature;

use App\Services\HistoricalData\HistoricalDataService;
use App\Services\NasdaqListings\NasdaqListingsService;
use Tests\TestCase;

class HistoricalDataServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function test_historical_data_service_gets_company_historical_data(): void
    {
        $historicalDataService = new HistoricalDataService(
            'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data',
            'fb06eebc46msh7adf1fd0b7bcee4p1519d3jsne400f43cec12',
            'yh-finance.p.rapidapi.com'
        );

        $historicalData = $historicalDataService->getHistoricalData();

        //Testing if we can get empty result without providing Company symbol
        $this->assertEmpty($historicalData, 'Historical data retrieved with No Company Symbol');

        try {
            $historicalDataService->getHistoricalData('WRONG_COMPANY_SYMBOL');
            $this->fail('For some reason getHistoricalData() method did not throw an exception after providing wrong company Symbol');
        } catch (\Exception $exception) {

            //Testing if we can get an exception (Message in this case) when providing wrong Company symbol

            $this->assertEquals(
                'The service temporary unavailable. Please try later. Thank you for your patience',
                $exception->getMessage()
            );
        }

        $nasdaqService = new NasdaqListingsService(
            'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'
        );
        $availableCompanySymbols = $nasdaqService->getAvailableCompanySymbols();
        $historicalData = $historicalDataService->getHistoricalData($availableCompanySymbols[0]);

        //Testing if we can retrieve historical data for a valid Company Symbol
        $this->assertNotEmpty($historicalData, 'No historical data was retrieved for a valid Company symbol');

        $historicalDataPrepared = $historicalDataService->getHistoricalDataPrepared('GOOG', '2023-09-01', '2023-09-20');

        //Testing if we can retrieve Prepared historical data for a valid Company Symbol
        $this->assertNotEmpty($historicalDataPrepared, 'No Prepared historical data was retrieved for a valid Company symbol');

        //Testing Prepared historical data structure for a valid Company Symbol
        $this->assertTrue(
            isset($historicalDataPrepared['chartDataOpenPrices']),
            'chartDataOpenPrices - key was not found in Prepared historical data for valid Company symbol'
        );
        $this->assertTrue(
            isset($historicalDataPrepared['chartDataClosePrices']),
            'chartDataClosePrices - key was not found in Prepared historical data for valid Company symbol'
        );
        $this->assertTrue(
            isset($historicalDataPrepared['chartDataDates']),
            'chartDataDates - key was not found in Prepared historical data for valid Company symbol'
        );
        $this->assertTrue(
            isset($historicalDataPrepared['historicalData']),
            'historicalData - key was not found in Prepared historical data for valid Company symbol'
        );

        //Testing Prepared historical data for a valid Company Symbol
        $this->assertNotEmpty(
            $historicalDataPrepared['chartDataOpenPrices'],
            'chartDataOpenPrices - field is empty in Prepared historical data for valid Company symbol'
        );
        $this->assertNotEmpty(
            $historicalDataPrepared['chartDataClosePrices'],
            'chartDataClosePrices - field is empty in Prepared historical data for valid Company symbol'
        );
        $this->assertNotEmpty(
            $historicalDataPrepared['chartDataDates'],
            'chartDataDates - field is empty in Prepared historical data for valid Company symbol'
        );
        $this->assertNotEmpty(
            $historicalDataPrepared['historicalData'],
            'historicalData - field is empty in Prepared historical data for valid Company symbol'
        );

        $historicalDataPrepared = $historicalDataService->getHistoricalDataPrepared('', '2023-09-01', '2023-09-20');

        //Testing if we can get empty result when we do not provide valid args
        $this->assertEmpty($historicalDataPrepared, 'No Prepared historical data was retrieved for a valid Company symbol');
    }
}
