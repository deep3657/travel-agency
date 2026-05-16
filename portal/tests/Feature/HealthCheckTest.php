<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_healthz_returns_ok_when_database_reachable(): void
    {
        $response = $this->getJson('/healthz');

        $response->assertOk();
        $response->assertJsonPath('status', 'ok');
        $response->assertJsonPath('checks.db.ok', true);
        $response->assertJsonStructure([
            'status',
            'checks' => [
                'db' => ['ok', 'latency_ms'],
                'queue' => ['ok', 'pending', 'oldest_pending_age_seconds'],
            ],
            'app' => ['version', 'env', 'time'],
        ]);
    }
}
