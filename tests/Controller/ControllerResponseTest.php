<?php declare(strict_types=1);

namespace Sofyco\Bundle\CorsResponseBundle\Tests\Controller;

use Sofyco\Bundle\CorsResponseBundle\EventListener\CorsResponseListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ControllerResponseTest extends WebTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        restore_exception_handler();
    }

    public function testResponseWithHeaders(): void
    {
        $response = $this->sendRequest();
        $headers = $response->headers->all();

        self::assertSame('{"status":"ok"}', $response->getContent());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayHasKey(CorsResponseListener::HEADER_ALLOW_ORIGIN, $headers);
        self::assertArrayHasKey(CorsResponseListener::HEADER_ALLOW_HEADERS, $headers);
        self::assertArrayHasKey(CorsResponseListener::HEADER_ALLOW_METHODS, $headers);
    }

    public function testResponseWithHeadersByOptionsRequest(): void
    {
        $response = $this->sendRequest(method: Request::METHOD_OPTIONS);
        $headers = $response->headers->all();

        self::assertSame('', $response->getContent());
        self::assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        self::assertArrayHasKey(CorsResponseListener::HEADER_ALLOW_ORIGIN, $headers);
        self::assertArrayHasKey(CorsResponseListener::HEADER_ALLOW_HEADERS, $headers);
        self::assertArrayHasKey(CorsResponseListener::HEADER_ALLOW_METHODS, $headers);
    }

    public function testResponseWithoutHeaders(): void
    {
        $response = $this->sendRequest(environment: 'dev');
        $headers = $response->headers->all();

        self::assertSame('{"status":"ok"}', $response->getContent());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertArrayNotHasKey(CorsResponseListener::HEADER_ALLOW_ORIGIN, $headers);
        self::assertArrayNotHasKey(CorsResponseListener::HEADER_ALLOW_HEADERS, $headers);
        self::assertArrayNotHasKey(CorsResponseListener::HEADER_ALLOW_METHODS, $headers);
    }

    private function sendRequest(string $method = Request::METHOD_GET, string $environment = 'test'): Response
    {
        $client = self::createClient(['environment' => $environment]);

        $client->request($method, '/', [], [], ['CONTENT_TYPE' => 'application/json']);

        return $client->getResponse();
    }
}
