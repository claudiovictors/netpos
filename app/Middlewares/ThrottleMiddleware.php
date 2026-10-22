<?php

declare(strict_types=1);

namespace App\Middlewares;

use Slenix\Http\Request;
use Slenix\Http\Response;
use Slenix\Http\Middlewares\Middleware;
use Slenix\Supports\Security\RateLimit;
use Slenix\Supports\Security\Jwt;

class ThrottleMiddleware implements Middleware
{
    private const DEFAULT_MAX           = 60;
    private const DEFAULT_DECAY_MINUTES = 1;

    public function handle(Request $request, Response $response, callable $next): mixed
    {
        [$maxAttempts, $decaySeconds] = $this->parseParams();
        $key    = $this->resolveKey($request);
        $result = RateLimit::attempt($key, $maxAttempts, $decaySeconds);
        $this->emitHeaders($result);

        if (!$result['allowed']) {
            return $this->respondTooManyRequests($request, $response, $result);
        }

        return $next($request, $response);
    }

    private function resolveKey(Request $request): string
    {
        return RateLimit::buildKey(
            route:      $this->normaliseRoute($request),
            ip:         $request->ip(),
            jwtUserId:  $this->extractJwtUserId($request),
            sessionKey: 'user_id'
        );
    }

    private function extractJwtUserId(Request $request): ?string
    {
        $authHeader = $request->getHeader('Authorization', '');
        if (!str_starts_with((string) $authHeader, 'Bearer ')) return null;
        $payload = (new Jwt())->validate(substr((string) $authHeader, 7));
        return ($payload && isset($payload['user_id'])) ? (string) $payload['user_id'] : null;
    }

    private function normaliseRoute(Request $request): string
    {
        $uri        = parse_url($request->uri(), PHP_URL_PATH) ?? '/';
        $normalised = preg_replace('/\/\d+/', '/{id}', $uri) ?? $uri;
        return 'throttle:' . trim($normalised, '/');
    }

    private function emitHeaders(array $result): void
    {
        if (headers_sent()) return;
        header('X-RateLimit-Limit: '     . $result['max_attempts']);
        header('X-RateLimit-Remaining: ' . $result['remaining']);
        header('X-RateLimit-Reset: '     . $result['reset_at']);
    }

    private function respondTooManyRequests(Request $request, Response $response, array $result): null
    {
        if (!headers_sent()) header('Retry-After: ' . $result['retry_after']);

        if ($request->expectsJson()) {
            $response->status(429)->json([
                'success'     => false,
                'message'     => 'Too many requests. Please slow down.',
                'retry_after' => $result['retry_after'],
                'reset_at'    => $result['reset_at'],
            ]);
        } else {
            http_response_code(429);
            echo '<h1>429 — Too Many Requests</h1>';
        }

        exit;
    }

    private function parseParams(): array
    {
        $raw = $_SERVER['HTTP_X_THROTTLE_PARAMS'] ?? '';

        if ($raw !== '' && str_starts_with($raw, 'throttle:')) {
            $parts = explode(',', substr($raw, strlen('throttle:')));
            $max   = (isset($parts[0]) && is_numeric($parts[0]) && (int)$parts[0] > 0) ? (int)$parts[0] : self::DEFAULT_MAX;
            $decay = (isset($parts[1]) && is_numeric($parts[1]) && (int)$parts[1] > 0) ? (int)$parts[1] : self::DEFAULT_DECAY_MINUTES;
            return [$max, $decay * 60];
        }

        return [self::DEFAULT_MAX, self::DEFAULT_DECAY_MINUTES * 60];
    }
}