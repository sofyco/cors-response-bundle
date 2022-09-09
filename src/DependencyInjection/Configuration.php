<?php declare(strict_types=1);

namespace Sofyco\Bundle\CorsResponseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('cors_response');

        /** @var ArrayNodeDefinition $root */
        $root = $builder->getRootNode();

        $options = $root->children();
        /** @var ArrayNodeDefinition $environments */
        $environments = $options->arrayNode('environments')->scalarPrototype()->end();
        $environments->defaultValue(['dev'])->end();
        $options->scalarNode('origin')->defaultValue('*')->end();
        $options->scalarNode('headers')->defaultValue('*')->end();
        $options->scalarNode('methods')->defaultValue('*')->end();

        return $builder;
    }
}
