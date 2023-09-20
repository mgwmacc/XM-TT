<?php

namespace Tests\Feature;

use App\Services\NasdaqListings\NasdaqListingsService;
use ReflectionClass;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Index page is available
     */
    public function test_index_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Absent page test
     */
    public function test_none_esiting_page_example(): void
    {
        $response = $this->get('/absent-page');

        $response->assertStatus(404);
    }
}
