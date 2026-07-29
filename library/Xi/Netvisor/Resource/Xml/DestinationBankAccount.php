<?php

namespace Xi\Netvisor\Resource\Xml;

class DestinationBankAccount
{
    private string $bankName;
    private string $bankBranch;
    private string $destinationBankAccountNumber;

    public function __construct(
        string $swift,
        string $iban,
        string $bankName = '',
    ) {
        $this->bankName = $bankName;
        $this->bankBranch = $swift;
        $this->destinationBankAccountNumber = $iban;
    }
}
