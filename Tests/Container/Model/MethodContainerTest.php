<?php

namespace WsdlToPhp\PackageGenerator\Tests\Container\Model;

use WsdlToPhp\PackageGenerator\Tests\Model\ServiceTest;
use WsdlToPhp\PackageGenerator\Model\Method;
use WsdlToPhp\PackageGenerator\Container\Model\Method as MethodContainer;
use WsdlToPhp\PackageGenerator\Tests\TestCase;

class MethodContainerTest extends TestCase
{
    /**
     * @return MethodContainer
     */
    public static function instance()
    {
        $service = ServiceTest::instance('Bar');
        $methodContainer = new MethodContainer();
        $methodContainer->add(new Method(self::getBingGeneratorInstance(), 'Foo', 'string', 'int', $service));
        $methodContainer->add(new Method(self::getBingGeneratorInstance(), 'Bar', 'string', 'int', $service));
        $methodContainer->add(new Method(self::getBingGeneratorInstance(), 'FooBar', array(
            'string',
            'int',
            'int',
        ), 'int', $service));
        return $methodContainer;
    }
    /**
     *
     */
    public function testGetMethodByName()
    {
        $methodContainer = self::instance();

        $this->assertInstanceOf('\\WsdlToPhp\\PackageGenerator\\Model\\Method', $methodContainer->getMethodByName('Foo'));
        $this->assertNull($methodContainer->getMethodByName('boo'));
    }
    /**
     *
     */
    public function testHasMethodWithScalarParameterType()
    {
        $methodContainer = self::instance();

        $this->assertTrue($methodContainer->hasMethod('Foo', 'string'));
        $this->assertFalse($methodContainer->hasMethod('Foo', 'int'));
    }
    /**
     *
     */
    public function testHasMethodWithArrayParameterType()
    {
        $methodContainer = self::instance();

        $this->assertTrue($methodContainer->hasMethod('FooBar', array(
            'string',
            'int',
            'int',
        )));
        $this->assertFalse($methodContainer->hasMethod('FooBar', array(
            'string',
            'string',
            'int',
        )));
    }
    /**
     *
     */
    public function testGetAs()
    {
        $methodContainer = new MethodContainer();

        $service = ServiceTest::instance('Bar');
        $foo = new Method(self::getBingGeneratorInstance(), 'Foo', 'string', 'int', $service);
        $bar = new Method(self::getBingGeneratorInstance(), 'Bar', 'string', 'int', $service);
        $methodContainer->add($foo);
        $methodContainer->add($bar);

        $this->assertSame($foo, $methodContainer->getAs(array(
            'name'          => 'Foo',
            'parameterType' => 'string',
            'returnType'    => 'int',
        )));

        $this->assertSame($bar, $methodContainer->getAs(array(
            'name'          => 'Bar',
            'parameterType' => 'string',
        )));
    }
}
