# Service Registry Bundle

Allows Symfony developers to add services into service registry without any further configuration needed.

## Instalation
```bash
composer require matejicekvojtech/service-registry-bundle
```
```php
# config/bundles.php
<?php

return [
// ...
    \MV\ServiceRegistryBundle\ServiceRegistryBundle::class => ['all' => true],
];
```

## Usage
In class to be put into registry add attribute with registry id and service priority in registry (higher number means higher priority)
```php
<?php

namespace App;

use MV\ServiceRegistryBundle\Attribute\ServiceInRegistry;

#[ServiceInRegistry(registry: 'some_registry_id', priority: 10 /* default 0 */)]
class SomeClass implements SomeInterface {
/* ... */
}
```

or in DI container definition add tag:
```yaml
services:
  App\SomeClass:
    tags:
      - { name: mv.service-in-registry, registry: some_registry_id, priority: 10 }
```

Service registry will be then available by service id `mv.service-registry.<registry_id>`, in example case `mv.service-registry.some_registry_id`

```php
<?php
namespace App;

use MV\ServiceRegistryBundle\Registry\ServiceRegistryInterface;

class ClassUsingRegistry {
    /**
     * @param ServiceRegistryInterface<SomeInterface> $serviceRegistry
     */
    public function __construct(
        private ServiceRegistryInterface $serviceRegistry,
    )
    
    public function useAllFromRegistry(): void
    {
        foreach ($this->serviceRegistry->all() as $service) {
            /* some logic here */
        }
    }
    
    public function useCertainFromRegistry(): void
    {
        $service = $this->serviceRegistry->get(App\SomeClass::class);
        /* some logic here */
    }
}
```
```yaml
services:
  App\ClassUsingRegistry:
    arguments:
      $serviceRegistry: '@mv.service-registry.some_registry_id'
```
