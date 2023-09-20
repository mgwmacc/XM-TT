<?php

namespace Tests\Feature;

use App\Services\NasdaqListings\NasdaqListingsService;
use ReflectionClass;
use Tests\TestCase;

class NasdaqListingServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function test_nasdaq_listings_service_gets_available_company_symbols(): void
    {
        $nasdaqService = new NasdaqListingsService(
            'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'
        );

        $availableCompanySymbols = $nasdaqService->getAvailableCompanySymbols();

        //Testing if we can get available company symbols
        $this->assertNotEmpty($availableCompanySymbols, 'No Available company symbols retrieved');

        //Testing if we can get company symbols and nothing more(not an array of arrays)
        $this->assertTrue(!is_array($availableCompanySymbols[0]), 'Available company symbols array structure is wrong');
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function test_nasdaq_listings_service_gets_available_companies(): void
    {
        $nasdaqService = new NasdaqListingsService(
            'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'
        );

        $class = new ReflectionClass(NasdaqListingsService::class);
        $method = $class->getMethod('getCompaniesData');

        // For PHP > 8.1.0
        $method->setAccessible(true);
        $availableCompanies = $method->invokeArgs($nasdaqService, []);

        //Testing if we can get available companies
        $this->assertNotEmpty($availableCompanies, 'No companies retrieved');

        //Testing if available companies data has right data
        $this->assertTrue(isset($availableCompanies[0]['Company Name']), '"Company Name" - key is not present in the retrieved Available Companies');
        $this->assertTrue(isset($availableCompanies[0]['Symbol']), '"Symbol" - key is not present in the retrieved Available Companies');
    }

    /**
     * @return void
     */
    public function test_nasdaq_listings_service_gets_company_name_by_symbol(): void
    {
        $nasdaqService = new NasdaqListingsService(
            'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json'
        );

        $availableCompanySymbols = $nasdaqService->getAvailableCompanySymbols();

        //Testing if we can get available company symbols
        $this->assertNotEmpty($availableCompanySymbols, 'No Available company symbols retrieved');

        $symbol = $availableCompanySymbols[0];

        $companyName = $nasdaqService->getCompanyNameBySymbol($symbol);

        //Testing if we can get company name by a company symbol
        $this->assertNotEmpty($companyName, '"Company Name" can not be retrieved for some reason');

        $companyName = $nasdaqService->getCompanyNameBySymbol('WRONG_COMPANY_SYMBOL');

        //Testing if we get empty string when provided wrong company symbol
        $this->assertEmpty($companyName, 'Some "Company Name" was retrieved instead of empty string');
    }
}
