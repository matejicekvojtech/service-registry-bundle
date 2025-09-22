<?php

namespace MV\ServiceRegistryBundle\Attribute;

use Attribute;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class ServiceInRegistry
{
    public function __construct(
        public string $registry,
        public int $priority = 0,
    )
    {
    }
}
