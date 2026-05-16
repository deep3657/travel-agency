<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    /**
     * Liveness + readiness probe used by Cloudflare/UptimeRobot.
     *
     * Returns:
     *  - 200 when DB connectivity is OK; queue lag is reported but never causes failure
     *    (lag is informational; alerting is handled separately).
     *  - 503 when the DB is unreachable.
     *
     * Response shape (HLD §17):
     * {
     *   "status": "ok" | "degraded" | "fail",
     *   "checks": { "db": { ... }, "queue": { ... } },
     *   "app": { "version": "...", "env": "...", "time": "..." }
     * }
     */
    public function __invoke(): JsonResponse
    {
        $checks = [
            'db' => $this->checkDatabase(),
            'queue' => $this->checkQueueLag(),
        ];

        $dbOk = $checks['db']['ok'];
        $queueOk = $checks['queue']['ok'];

        if (! $dbOk) {
            $status = 'fail';
        } elseif (! $queueOk) {
            $status = 'degraded';
        } else {
            $status = 'ok';
        }

        $http = $dbOk ? 200 : 503;

        return response()->json([
            'status' => $status,
            'checks' => $checks,
            'app' => [
                'version' => config('app.version', 'dev'),
                'env' => config('app.env'),
                'time' => now()->toIso8601String(),
            ],
        ], $http);
    }

    /**
     * @return array{ok: bool, latency_ms: float|null, error?: string}
     */
    private function checkDatabase(): array
    {
        $start = microtime(true);

        try {
            DB::connection()->getPdo();
            DB::select('SELECT 1');

            return [
                'ok' => true,
                'latency_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'latency_ms' => null,
                'error' => 'database_unavailable',
            ];
        }
    }

    /**
     * Reads pending jobs and oldest reserved-at from the `jobs` table.
     *
     * "Lag" = age of the oldest pending job in seconds. If `jobs` table does
     * not exist yet (fresh install before migrations), returns ok=true with
     * zero pending. Threshold of 300s for "ok" matches HLD §9.5 SLA.
     *
     * @return array{ok: bool, pending: int, oldest_pending_age_seconds: int|null, error?: string}
     */
    private function checkQueueLag(): array
    {
        try {
            if (! DB::getSchemaBuilder()->hasTable('jobs')) {
                return [
                    'ok' => true,
                    'pending' => 0,
                    'oldest_pending_age_seconds' => null,
                ];
            }

            $pending = (int) DB::table('jobs')->count();
            $oldest = DB::table('jobs')->min('available_at');

            $age = $oldest === null ? null : max(0, time() - (int) $oldest);
            $thresholdSeconds = 300;

            return [
                'ok' => $age === null || $age <= $thresholdSeconds,
                'pending' => $pending,
                'oldest_pending_age_seconds' => $age,
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'pending' => 0,
                'oldest_pending_age_seconds' => null,
                'error' => 'queue_check_failed',
            ];
        }
    }
}
