<?php

namespace Xi\Netvisor\Component;

use JMS\Serializer\Metadata\PropertyMetadata;
use PHPUnit\Framework\TestCase;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

class LowercaseNamingStrategyTest extends TestCase
{
    #[Test]
    #[DataProvider('provideNames')]
    public function lowercases($name)
    {
        $strategy = new LowercaseNamingStrategy();

        $toBeObject = array();
        $toBeObject[$name] = '';
        $object = (object)$toBeObject;

        $metadata = new PropertyMetadata($object::class, $name);

        $this->assertEquals(strtolower($name), $strategy->translateName($metadata));
    }

    public static function provideNames()
    {
        return array(
            array('camelCase'),
            array('CamelCaps'),
            array('CAPSLOCKCAPSLOCK'),
        );
    }
}
