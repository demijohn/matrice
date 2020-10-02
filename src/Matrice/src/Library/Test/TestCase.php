<?php
declare(strict_types=1);

namespace Matrice\Library\Test;

use Laminas\Diactoros\ServerRequest;
use Mezzio\Application;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseInterface;

abstract class TestCase extends BaseTestCase
{
    use ProphecyTrait;

    private const DEFAULT_REQUEST_HEADERS = [
        'Content-Type' => 'application/json',
    ];

    protected Application $app;

    protected function get(string $uri, array $headers = [], array $queryParams = []): ResponseInterface
    {
        $headers = array_merge($headers, self::DEFAULT_REQUEST_HEADERS);
        $request = new ServerRequest([], [], $uri, 'GET', 'php://memory', $headers, [], $queryParams);

        return $this->app->handle($request);
    }

    protected function post(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        $headers = array_merge($headers, self::DEFAULT_REQUEST_HEADERS);
        $request = new ServerRequest([], [], $uri, 'POST', 'php://memory', $headers);
        $request->getBody()
            ->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $this->app->handle($request);
    }

    protected function patch(string $uri, array $data = [], array $headers = []): ResponseInterface
    {
        $headers = array_merge($headers, self::DEFAULT_REQUEST_HEADERS);
        $request = new ServerRequest([], [], $uri, 'PATCH', 'php://memory', $headers);
        $request->getBody()
            ->write(json_encode($data, JSON_THROW_ON_ERROR));

        return $this->app->handle($request);
    }

    protected function assertResponse(int $statusCode, ?array $subset, ResponseInterface $response): void
    {
        $this->assertSame($statusCode, $response->getStatusCode());

        $body = (string) $response->getBody();

        if ($subset !== null) {
            $resource = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

            $expected = array_replace_recursive($resource, $subset);

            $this->assertSame($expected, $resource);
        } else {
            $this->assertEmpty($body);
        }
    }

    protected function getResponseData(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();
        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return $data['_embedded'];
    }
}
