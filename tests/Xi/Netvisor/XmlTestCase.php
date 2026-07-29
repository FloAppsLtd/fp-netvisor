<?php

namespace Xi\Netvisor;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;
use PHPUnit\Framework\TestCase;

class XmlTestCase extends TestCase
{
    private Serializer $serializer;

    private Validate $validate;

    public function setUp(): void
    {
        $builder = SerializerBuilder::create();
        $builder->setPropertyNamingStrategy(new LowercaseNamingStrategy());

        $this->serializer = $builder->build();
        $this->validate = new Validate();
    }

    public function toXml(object $object): string
    {
        return $this->serializer->serialize($object, 'xml');
    }

    public function assertXmlContainsTagWithValue(string $tag, mixed $value, string $xml): void
    {
        $this->assertStringContainsString(sprintf('<%s', $tag), $xml);

        if (is_int($value) || is_float($value)) {
            $this->assertStringContainsString(sprintf('>%s</%s>', $value, $tag), $xml);
            return;
        }

        $this->assertStringContainsString(sprintf('><![CDATA[%s]]></%s>', $value, $tag), $xml);
    }

    public function assertXmlDoesNotContainTag(string $tag, string $xml): void
    {
        $this->assertStringNotContainsString(sprintf('<%s', $tag), $xml);
    }

    public function assertXmlContainsTagWithAttributes(string $tag, array $attributes, string $xml): void
    {
        $attributeLine = '';

        foreach ($attributes as $key => $value) {
            $attributeLine .= sprintf(' %s="%s"', $key, $value);
        }

        $this->assertStringContainsString(sprintf('<%s%s>', $tag, $attributeLine), $xml);
    }

    public function assertXmlIsValid(string $xml, string $dtdPath): void
    {
        $this->assertTrue($this->validate->isValid($xml, $dtdPath));
    }
}
