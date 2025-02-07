<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\RequireJsonMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\TestCase;

class RequireJsonMiddlewareTest extends TestCase
{
    public function test_api_request_without_json_header(): void
    {

        $request = $this->createRequest('get', '/api/v1/');
        $next = function () {
            return new SymfonyResponse;
        };

        $response = (new RequireJsonMiddleware)->handle($request, $next);

        $this->assertEquals(Response::HTTP_NOT_ACCEPTABLE, $response->getStatusCode());
    }

    public function test_api_request_with_json_header(): void
    {

        $request = $this->createRequest('get', '/api/v1/');
        $request->headers->set('Accept', 'application/json');
        $next = function () {
            return new SymfonyResponse;
        };

        $response = (new RequireJsonMiddleware)->handle($request, $next);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    private function createRequest(string $method, string $uri): Request
    {

        $symfonyRequest = SymfonyRequest::create($uri, $method);

        return Request::createFromBase($symfonyRequest);
    }
}
