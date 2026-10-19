<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Log;

class SearchFailureTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_search_handles_database_exception_gracefully()
    {
        // Break the database connection dynamically
        \Illuminate\Support\Facades\DB::disconnect();
        config(['database.default' => 'invalid_connection']);

        // Expect the error to be logged once
        Log::shouldReceive('error')->once();

        $response = $this->get('/buscar_profesor?profesor=juan');

        $response->assertStatus(200);
        $response->assertSee(__('messages.search_error'));

        // Restore connection default
        config(['database.default' => 'sqlite']);
    }
}
