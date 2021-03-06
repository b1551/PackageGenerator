<?php

namespace WsdlToPhp\PackageGenerator\Generator;

use WsdlToPhp\PackageGenerator\Container\Model\Struct as StructContainer;
use WsdlToPhp\PackageGenerator\Container\Model\Service as ServiceContainer;

class GeneratorContainers extends AbstractGeneratorAware
{
    /**
     * Structs
     * @var StructContainer
     */
    private $structs;
    /**
     * Services
     * @var ServiceContainer
     */
    private $services;
    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
        $this
            ->initStructs()
            ->initServices();
    }
    /**
     * @return GeneratorContainers
     */
    protected function initStructs()
    {
        if (!isset($this->structs)) {
            $this->structs = new StructContainer($this->generator);
        }
        return $this;
    }
    /**
     * @return GeneratorContainers
     */
    protected function initServices()
    {
        if (!isset($this->services)) {
            $this->services = new ServiceContainer($this->generator);
        }
        return $this;
    }
    /**
     * @return ServiceContainer
     */
    public function getServices()
    {
        return $this->services;
    }
    /**
     * @return StructContainer
     */
    public function getStructs()
    {
        return $this->structs;
    }
}
