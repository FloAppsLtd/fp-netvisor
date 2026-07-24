<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\TestResource;
use Xi\Netvisor\XmlTestCase;
use PHPUnit\Framework\Attributes\Test;

class TestResourceTest extends XmlTestCase
{
    #[Test]
    public function convertsToXml()
    {
        $resource = new TestResource();
        $resource->setValue('value');

        $this->assertStringStartsWith('<?xml', $this->toXml($resource->getSerializableObject()));
    }
}
