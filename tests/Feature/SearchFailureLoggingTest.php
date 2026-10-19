<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Log;

class SearchFailureLoggingTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_search_returns_fallback_when_logging_fails()
    {
        // Break the database connection dynamically
        \Illuminate\Support\Facades\DB::disconnect();
        config(['database.default' => 'invalid_connection']);

        // Make logging throw as well
        Log::shouldReceive('error')->once()->andThrow(new \Exception('Log failing'));

        $response = $this->get('/buscar_profesor?profesor=juan');

        $response->assertStatus(200);
        $response->assertSee(__('messages.search_error'));

        // Restore connection default
        config(['database.default' => 'sqlite']);
    }
}
