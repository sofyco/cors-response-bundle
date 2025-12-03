<?php declare(strict_types=1);

namespace Sofyco\Bundle\CorsResponseBundle\DependencyInjection;

use Sofyco\Bundle\CorsResponseBundle\EventListener\CorsResponseListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\HttpKernel\KernelEvents;

final class CorsResponseExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $environment = $container->getParameter('kernel.environment');

        if (false === is_array($mergedConfig['environments'])) {
            return;
        }

        if (false === in_array($environment, $mergedConfig['environments'], true)) {
            return;
        }

        $listener = new Definition(CorsResponseListener::class, [
            $mergedConfig['origin'],
            $mergedConfig['headers'],
            $mergedConfig['methods'],
        ]);
        $listener->addTag('kernel.event_listener', ['event' => KernelEvents::RESPONSE]);

        $container->setDefinition(CorsResponseListener::class, $listener);
    }
}
