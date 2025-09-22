<?php
declare(strict_types=1);

namespace MV\ServiceRegistryBundle\Registry;

/**
 * @template T of object
 * @implements RegistryInterface<T>
 */
class ServiceRegistry implements RegistryInterface
{
    /**
     * @var iterable<class-string, T>
     */
    private iterable $services = [];

    /**
     * @param iterable<T> $services
     */
    public function __construct(
        iterable $services = [],
    )
    {
        foreach ($services as $service) {
            $this->services[$service::class] = $service;
        }
    }

    /**
     * @inheritDoc
     */
    public function all(): iterable
    {
        return $this->services;
    }

    /**
     * @inheritDoc
     */
    public function get(string $className): ?object
    {
        return $this->services[$className] ?? null;
    }
}
