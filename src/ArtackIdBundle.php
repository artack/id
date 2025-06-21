<?php

declare(strict_types=1);

namespace Artack\Id;

use Artack\Id\DependencyInjection\CompilerPass\DoctrineTypeRegisterCompilerPass;
use Artack\Id\Doctrine\Driver\MariaDBDriverMiddleware;
use Artack\Id\Doctrine\Platform\MariaDB1011Platform;
use Artack\Id\Serializer\IdDenormalizer;
use Artack\Id\Serializer\IdNormalizer;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ArtackIdBundle extends AbstractBundle {

    public const string CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY = 'doctrine_type_directories';
    public const string PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY = 'artack.id.doctrine_type_directories';

    public const string CONFIG_SERIALIZER_KEY = 'serializer';
    public const string CONFIG_SERIALIZER_ENABLE_KEY = 'enable';
    public const string SERIALIZER_ENABLE_KEY = 'serializer';
    public const string SERIALIZER_ENABLE_OPTION_KEY = 'enable';

    public const string PLATFORM_MARIADB1011_ENABLE_KEY = 'platform_mariadb1011';
    public const string PLATFORM_MARIADB1011_ENABLE_OPTION_KEY = 'enable';

    public const string PARAMETER_SERIALIZER_ENABLE_KEY = 'artack.id.serializer.enable';

    #[Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineTypeRegisterCompilerPass());
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter(self::PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY, $config[self::CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY]);

//        $builder->setParameter(self::PARAMETER_SERIALIZER_ENABLE_KEY, $serializerEnabled);

        $serializerEnabled = $config[self::CONFIG_SERIALIZER_KEY][self::CONFIG_SERIALIZER_ENABLE_KEY] ?? false;
        if ($serializerEnabled) {
            if (!interface_exists(NormalizerInterface::class)) {
                throw new \LogicException('Unable register the Denormalizers and Normalizers as the Symfony Serializer Component is not installed. Try running "composer require symfony/serializer" or set serializer.enable to false.');
            }

            $services = $container->services();

            $services->set('artack.id.serializer.normalizer', IdNormalizer::class)
                ->tag('serializer.normalizer');

            $services->set('artack.id.serializer.denormalizer', IdDenormalizer::class)
                ->tag('serializer.normalizer');
        }

        $platformMariadb1011Enabled = $config[self::PLATFORM_MARIADB1011_ENABLE_KEY][self::PLATFORM_MARIADB1011_ENABLE_OPTION_KEY] ?? false;
        if ($platformMariadb1011Enabled) {
            $services = $container->services();

            $services->set('artack.id.platform.mariadb1011', MariaDBDriverMiddleware::class)
                ->tag('doctrine.middleware')
            ;
        }
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
                ->arrayNode(self::PLATFORM_MARIADB1011_ENABLE_KEY)
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode(self::PLATFORM_MARIADB1011_ENABLE_OPTION_KEY)
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode(self::SERIALIZER_ENABLE_KEY)
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode(self::SERIALIZER_ENABLE_OPTION_KEY)
                            ->info('Enable or disable the serializer components')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
