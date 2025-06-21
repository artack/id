<?php

declare(strict_types=1);

namespace Artack\Id\DependencyInjection\CompilerPass;

use Artack\Id\ArtackIdBundle;
use Doctrine\DBAL\Types\Type;
use Generator;
use League\ConstructFinder\ConstructFinder;
use Override;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmozart\Assert\Assert;

use function array_key_exists;

final readonly class DoctrineTypeRegisterCompilerPass implements CompilerPassInterface
{
    private const string DOCTRINE_DBAL_TYPE_DEFINITION_PARAMETER = 'doctrine.dbal.connection_factory.types';
    private const string TYPE_NAME = 'NAME';

    #[Override]
    public function process(ContainerBuilder $container): void
    {
        /**
         * @var array<string, array{class: class-string}> $typeDefinitions
         */
        $typeDefinitions = $container->getParameter(self::DOCTRINE_DBAL_TYPE_DEFINITION_PARAMETER);

        /**
         * @var array<int, string>
         */
        $doctrineTypeDirectories = $container->getParameter(ArtackIdBundle::PARAMETER_DOCTRINE_TYPE_DIRECTORIES_KEY);

        foreach ($doctrineTypeDirectories as $doctrineTypeDirectory) {
            $types = $this->findTypesInDirectory($doctrineTypeDirectory);

            foreach ($types as $type) {
                $name = $type['name'];
                $class = $type['class'];

                if (array_key_exists($name, $typeDefinitions)) {
                    continue;
                }

                $typeDefinitions[$name] = ['class' => $class];
            }
        }

        $container->setParameter(self::DOCTRINE_DBAL_TYPE_DEFINITION_PARAMETER, $typeDefinitions);
    }

    /**
     * @return Generator<int, array{class: class-string<Type>, name: string}>
     */
    private function findTypesInDirectory(string $pathToDoctrineTypeDirectory): iterable
    {
        $classNames = ConstructFinder::locatedIn($pathToDoctrineTypeDirectory)->findClassNames();

        foreach ($classNames as $className) {
            $reflection = new ReflectionClass($className);
            if (!$reflection->isSubclassOf(Type::class)) {
                continue;
            }

            // Don't register parent types
            if ($reflection->isAbstract()) {
                continue;
            }

            // Only register types that have the relevant method
            if (!$reflection->hasConstant(self::TYPE_NAME)) {
                continue;
            }

            $typeName = $className::{self::TYPE_NAME};
            Assert::string($typeName);

            yield [
                'class' => $reflection->getName(),
                'name' => $typeName,
            ];
        }
    }
}
