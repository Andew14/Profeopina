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
        // Mock the Eloquent static call to throw when trying to query
        Mockery::mock('alias:App\\Models\\Profesor')
            ->shouldReceive('where')
            ->andThrow(new \Exception('DB down'));

        // Expect the error to be logged once
        Log::shouldReceive('error')->once();

        $response = $this->get('/buscar_profesor?profesor=juan');

        $response->assertStatus(200);
        $response->assertSee(__('messages.search_error'));
    }
}
