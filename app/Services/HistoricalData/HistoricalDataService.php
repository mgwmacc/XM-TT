<?php

namespace App\Services\HistoricalData;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;


class HistoricalDataService
{
    /**
     * @var readonly|string
     */
    private readonly string $baseUrl;

    /**
     * @var readonly|string
     */
    private readonly string $key;

    /**
     * @var readonly|string
     */
    private readonly string $host;

    /**
     * @param string $baseUrl
     * @param string $key
     * @param string $host
     */
    public function __construct(
        string $baseUrl = '',
        string $key = '',
        string $host = ''
    ) {
        $this->baseUrl = $baseUrl;
        $this->key     = $key;
        $this->host    = $host;
    }

    /**
     * @param string $symbol
     * @return array|mixed
     */
    public function getHistoricalData(string $symbol = '')
    {
        if (!$symbol) {
            return [];
        }

        try {
            $result = Http::withHeaders([
                'X-RapidAPI-Key'  => $this->key,
                'X-RapidAPI-Host' => $this->host
            ])->timeout(3)->get(
                $this->baseUrl, [
                    'symbol' => $symbol
                ]
            )->json();

        } catch (\Exception $e) {
            abort(500, 'The service temporary unavailable. Please try later. Thank you for your patience');
        }

        return $result;
    }

    /**
     * @param string $symbol
     * @param string $dateStart
     * @param string $dateEnd
     * @return array|array[]
     */
    public function getHistoricalDataPrepared(
        string $symbol = '',
        string $dateStart = '',
        string $dateEnd = ''
    ): array {
        if (!$dateStart || !$dateEnd || !$symbol) {
            return [];
        }

        $result = [
            'chartDataOpenPrices'  => [],
            'chartDataClosePrices' => [],
            'chartDataDates'       => [],
            'historicalData'       => []
        ];

        $historicalData = $this->getHistoricalData($symbol);

        if (isset($historicalData['prices']) && $historicalData['prices']) {
            $date = array_column($historicalData['prices'], 'date');

            array_multisort($date, SORT_ASC, $historicalData['prices']);

            foreach ($historicalData['prices'] as $k => &$item) {
                $carbonObject = Carbon::createFromTimestamp($item['date']);
                $dateString   = $carbonObject->toDateString();

                if (
                    $dateString < $dateStart ||
                    $dateString > $dateEnd
                ) {
                    unset($historicalData['prices'][$k]);
                    continue;
                }

                $item['date'] = $dateString;

                $result['chartDataOpenPrices'][]  = $item['open'];
                $result['chartDataClosePrices'][] = $item['close'];
                $result['chartDataDates'][]       = $carbonObject->toDateTimeString();
            }

            $result['historicalData'] = $historicalData['prices'];
        }

        return $result;
    }
}
