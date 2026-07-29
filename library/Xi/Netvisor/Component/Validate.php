<?php

declare(strict_types=1);

namespace Xi\Netvisor\Component;

use Exception;

class Validate
{
    /**
     * Validates the given XML against DTD.
     *
     * @param  string  $filepath to DTD
     */
    public function isValid(string $xml, string $filepath): bool
    {
        $xml = $this->insertDtd($xml, $filepath);

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $previousUseInternalErrors = libxml_use_internal_errors(true);

        try {
            return $dom->validate();
        } catch (Exception) {
            return false;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
        }
    }

    private function insertDtd(string $xml, string $filepath): string
    {
        $dtd = @file_get_contents($filepath);

        $xml = explode("\n", $xml);
        $xml[0] .= "\n" . $dtd;

        return implode("\n", $xml);
    }
}
