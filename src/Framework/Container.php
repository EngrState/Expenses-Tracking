<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerExecption;

class Container
{
    private array $definitions = [];
    private array $resolved= [];

    public function addDefinitions(array $newDefinitions)
    {
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }
    public function  resolve(string $className)
    {
        $reflrctionClass = new ReflectionClass($className);

        if (!$reflrctionClass->isInstantiable()) {
            throw new ContainerExecption("Class {$className} is not instantiable");
        }

        $constructor = $reflrctionClass->getConstructor();
        if (!$constructor) {
            return new $className;
        }
        $params = $constructor->getParameters();

        if (count($params) === 0) {
            return new $className;
        }

        $dependencies = [];
        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();
            if (!$type) {
                throw new ContainerExecption("failed to resolve class{$className} because param{$name} is missing a type hint.");
            }
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerExecption("failed to resolve class{$className} because invalid param name.");
            }

            $dependencies[]= $this->get($type->getName());
        }

        return $reflrctionClass->newInstanceArgs($dependencies);
    }
    public function get(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerExecption("this {$id} does not exist in container.");
        }

        if(array_key_exists($id, $this->resolved)){
            return $this->resolved[$id];
        }

        $factory = $this->definitions[$id];
        $dependencies = $factory($this);

        $this->resolved[$id] = $dependencies;

        return $dependencies;
    }
}
