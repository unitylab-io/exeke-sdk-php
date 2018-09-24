<?php

namespace Exeke\Types;

use Exeke\Utils\Inflector;

abstract class AbstractType {
  public function __construct(array $attributes = []) {
    $refl = new \ReflectionClass($this);
    foreach ($attributes as $key => $value) {
      $attributeName = Inflector::camelize($key);
      if (!$refl->hasProperty($attributeName)) {
        continue;
      }

      $setterMethodName = 'set' . Inflector::classify($key);
      if ($refl->hasMethod($setterMethodName)) {
        $method = $refl->getMethod($setterMethodName);
        $method->invoke($this, $value);
        continue;
      }

      $property = $refl->getProperty($attributeName);
      if ($property instanceof \ReflectionProperty) {
        $property->setValue($this, $value);
      }
    }
  }
}
