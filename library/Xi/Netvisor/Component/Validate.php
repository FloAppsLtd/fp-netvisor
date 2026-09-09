<?php

namespace Xi\Netvisor\Component;

class Validate
{
    /**
     * Validates the given XML against DTD.
     *
     * @param  string  $xml
     * @param  string  $filepath to DTD
     * @return boolean
     */
    public function isValid($xml, $filepath)
    {
        $xml = $this->insertDtd($xml, $filepath);

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $previousUseInternalErrors = libxml_use_internal_errors(true);

        try {
            return $dom->validate();
        } catch (\Exception) {
            return false;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousUseInternalErrors);
        }
    }

    /**
     * @param  string $xml
     * @param  string $filepath
     * @return string
     */
    private function insertDtd($xml, $filepath)
    {
        $dtd = @file_get_contents($filepath);

        $xml = explode("\n", $xml);
        $xml[0] .= "\n" . $dtd;

        return implode("\n", $xml);
    }
}
