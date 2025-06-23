<?php

declare(strict_types=1);

namespace Artack\Id;

use Artack\Id\DependencyInjection\CompilerPass\DoctrineTypeRegisterCompilerPass;
use Artack\Id\Doctrine\Driver\MariaDBDriverMiddleware;
use Artack\Id\Serializer\IdDenormalizer;
use Artack\Id\Serializer\IdNormalizer;
use LogicException;
use Override;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ArtackIdBundle extends AbstractBundle
{
    public const string PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY = 'artack.id.doctrine_type_directories';

    private const string CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY = 'doctrine_type_directories';
    private const string CONFIG_SERIALIZER = 'serializer';
    private const string CONFIG_PLATFORM_MARIADB1011 = 'platform_mariadb1011';

    #[Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineTypeRegisterCompilerPass());
    }

    /**
     * @param array{doctrine_type_directories:array<int,string>, platform_mariadb1011:array{enabled:bool}, serializer:array{enabled:bool}} $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter(self::PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY, $config[self::CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY]);

        if ($config[self::CONFIG_SERIALIZER]['enabled']) {
            if (!interface_exists(NormalizerInterface::class)) {
                throw new LogicException('Unable register the Denormalizers and Normalizers as the Symfony Serializer Component is not installed. Try running "composer require symfony/serializer" or set serializer.enable to false.');
            }

            $services = $container->services();

            $services->set('artack.id.serializer.normalizer', IdNormalizer::class)
                ->tag('serializer.normalizer')
            ;

            $services->set('artack.id.serializer.denormalizer', IdDenormalizer::class)
                ->tag('serializer.normalizer')
            ;
        }

        if ($config[self::CONFIG_PLATFORM_MARIADB1011]['enabled']) {
            $services = $container->services();

            $services->set('artack.id.platform.mariadb1011', MariaDBDriverMiddleware::class)
                ->tag('doctrine.middleware')
            ;
        }
    }

    #[Override]
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode() // @phpstan-ignore-line
            ->children()
            ->arrayNode(self::CONFIG_DOCTRINE_TYPE_DIRECTORIES_KEY)
            ->info('List of full file paths to directories to scan for Doctrine types')
            ->beforeNormalization()->castToArray()->end()
            ->defaultValue([])
            ->prototype('variable')->end()
            ->end()
            ->arrayNode(self::CONFIG_PLATFORM_MARIADB1011)->canBeEnabled()->end()
            ->arrayNode(self::CONFIG_SERIALIZER)->canBeEnabled()->end()
            ->end()
        ;
    }
}
