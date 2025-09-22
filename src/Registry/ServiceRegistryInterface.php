<?php
declare(strict_types=1);

namespace MV\ServiceRegistryBundle\Registry;

/**
 * @template T of object
 */
interface ServiceRegistryInterface
{
    /**
     * @return iterable<class-string<T>, T>
     */
    public function all(): iterable;

    /**
     * @param class-string<T> $className
     * @return T|null
     */
    public function get(string $className): ?object;
}
