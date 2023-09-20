<?php

namespace App\Services\NasdaqListings;

use Illuminate\Support\Facades\Http;

class NasdaqListingsService
{
    /**
     * @var readonly|string
     */
    private readonly string $baseUrl;

    /**
     * @var array
     */
    private static array $companiesData = [];

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl = '') {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return array
     */
    public function getAvailableCompanySymbols(): array
    {
        $result = [];

        if (!self::$companiesData) {
            self::$companiesData = $this->getCompaniesData();
        }

        if (!self::$companiesData) {
            return $result;
        }

        foreach (self::$companiesData as $item) {
            $result[] = $item['Symbol'];
        }

        return $result;
    }

    /**
     * @param string $symbol
     * @return string
     */
    public function getCompanyNameBySymbol(string $symbol = ''): string
    {
        if (!$symbol ) {
            return '';
        }

        if (!self::$companiesData) {
            self::$companiesData = $this->getCompaniesData();
        }

        if (!self::$companiesData) {
            return '';
        }

        foreach (self::$companiesData as $item) {
            if ($item['Symbol'] == $symbol) {
                return $item['Company Name'] ?? '';
            }
        }

        return '';
    }

    /**
     * @return array
     */
    private function getCompaniesData(): array
    {
        $result = [];

        try {
            $result = Http::timeout(3)->get(
                $this->baseUrl
            )->json();
        } catch (\Exception $e) {
            abort(500, 'The service temporary unavailable. Please try later. Thank you for your patience');
        }

        return $result;
    }
}
