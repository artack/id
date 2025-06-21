<?php

declare(strict_types=1);

namespace Artack\Id;

use Artack\Id\DependencyInjection\CompilerPass\DoctrineTypeRegisterCompilerPass;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ArtackIdBundle extends AbstractBundle {

    public const CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY = 'doctrine_type_directories';
    public const PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY = 'artack.id.doctrine_type_directories';

    #[Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineTypeRegisterCompilerPass());
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter(self::PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY, $config[self::CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY]);;
    }


    #[Override]
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode(self::CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY)
                    ->info('List of full file paths to directories to scan for Doctrine types')
                    ->beforeNormalization()->castToArray()->end()
                    ->defaultValue([])
                    ->prototype('variable')->end()
                ->end()
            ->end()
        ;
    }
}
