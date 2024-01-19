<?php declare(strict_types=1);

namespace Sofyco\Bundle\CorsResponseBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final readonly class CorsResponseListener
{
    public const string HEADER_ALLOW_ORIGIN = 'access-control-allow-origin';
    public const string HEADER_ALLOW_HEADERS = 'access-control-allow-headers';
    public const string HEADER_ALLOW_METHODS = 'access-control-allow-methods';

    public function __construct(private string $origin, private string $headers, private string $methods)
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $event->getResponse()->headers->add([
            self::HEADER_ALLOW_ORIGIN => $this->origin,
            self::HEADER_ALLOW_HEADERS => $this->headers,
            self::HEADER_ALLOW_METHODS => $this->methods,
        ]);

        if (Request::METHOD_OPTIONS === $event->getRequest()->getMethod()) {
            $event->getResponse()->setStatusCode(Response::HTTP_NO_CONTENT);
        }
    }
}
