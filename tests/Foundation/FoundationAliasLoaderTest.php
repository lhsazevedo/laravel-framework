<?php

namespace Illuminate\Tests\Foundation;

use Illuminate\Foundation\AliasLoader;
use PHPUnit\Framework\TestCase;

class FoundationAliasLoaderTest extends TestCase
{
    public function testLoaderCanBeCreatedAndRegisteredOnce()
    {
        $loader = AliasLoader::getInstance(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], $loader->getAliases());
        $this->assertFalse($loader->isRegistered());
        $loader->register();

        $this->assertTrue($loader->isRegistered());
    }

    public function testGetInstanceCreatesOneInstance()
    {
        $loader = AliasLoader::getInstance(['foo' => 'bar']);
        $this->assertSame($loader, AliasLoader::getInstance());
    }

    public function testLoaderCanBeCreatedAndRegisteredMergingAliases()
    {
        $loader = AliasLoader::getInstance(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $loader->getAliases());

        $loader = AliasLoader::getInstance(['foo2' => 'bar2']);
        $this->assertEquals(['foo2' => 'bar2', 'foo' => 'bar'], $loader->getAliases());

        // override keys
        $loader = AliasLoader::getInstance(['foo' => 'baz']);
        $this->assertEquals(['foo2' => 'bar2', 'foo' => 'baz'], $loader->getAliases());
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoaderCanAliasAndLoadClasses()
    {
        $loader = AliasLoader::getInstance(['some_alias_foo' => FoundationAliasLoaderStub::class]);

        $result = $loader->load('some_alias_foo');

        $this->assertInstanceOf(FoundationAliasLoaderStub::class, new \some_alias_foo);
        $this->assertTrue($result);

        $result2 = $loader->load('bar');
        $this->assertNull($result2);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetAlias()
    {
        $loader = AliasLoader::getInstance();
        $loader->setAliases(['some_alias_foo' => FoundationAliasLoaderStub::class]);

        $result = $loader->load('some_alias_foo');

        $fooObj = new \some_alias_foo;
        $this->assertInstanceOf(FoundationAliasLoaderStub::class, $fooObj);
        $this->assertTrue($result);
    }
}

class FoundationAliasLoaderStub
{
    //
}
