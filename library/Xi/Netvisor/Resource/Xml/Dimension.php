<?php

declare(strict_types=1);

namespace Xi\Netvisor\Resource\Xml;

class Dimension
{
    private string $dimensionname;
    private string $dimensionitem;

    public function __construct(string $dimensionname, string $dimensionitem)
    {
        $this->dimensionname = $dimensionname;
        $this->dimensionitem = $dimensionitem;
    }
}
