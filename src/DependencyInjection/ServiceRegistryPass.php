<?php

namespace MV\ServiceRegistryBundle\DependencyInjection;

use MV\ServiceRegistryBundle\Attribute\ServiceInRegistry;
use MV\ServiceRegistryBundle\Registry\ServiceServiceRegistry;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ServiceRegistryPass implements CompilerPassInterface
{

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        $registries = [];

        foreach ($container->getDefinitions() as $id => $definition) {
            $class = $definition->getClass();

            if (! $class || ! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            foreach ($reflection->getAttributes(ServiceInRegistry::class) as $attribute) {
                /** @var ServiceInRegistry $meta */
                $meta = $attribute->newInstance();
                $this->addToRegistry($registries, $meta->registry, $id, $meta->priority);
            }

            if ($definition->hasTag('mv.service-in-registry')) {
                foreach ($definition->getTag('mv.service-in-registry') as $tag) {
                    $registry = $tag['registry'] ?? null;
                    if (null === $registry) {
                        continue;
                    }
                    $priority = $tag['priority'] ?? 0;
                    $this->addToRegistry($registries, $registry, $id, $priority);
                }
            }
        }

        foreach ($registries as $registryId => $services) {
            usort(
                $services,
                static fn ($a, $b) => $b['priority'] <=> $a['priority'],
            );

            $definition = new Definition(ServiceServiceRegistry::class, [
                array_map(
                    static fn (array $service) => new Reference($service['id']),
                    $services,
                ),
            ]);

            $container->setDefinition(
                sprintf('mv.service-registry.%s', $registryId),
                $definition,
            );
        }
    }

    private function addToRegistry(array &$registries, string $registryId, string $serviceId, int $priority): void
    {
        $registries[$registryId][$serviceId] = [
            'priority' => $priority,
            'id' => $serviceId,
        ];
    }
}
