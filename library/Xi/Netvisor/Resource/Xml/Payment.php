<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class Payment extends Root
{
    private string $bankPaymentMessageType;
    private string $bankPaymentMessage;
    private Recipient $recipient;
    private string $sourceBankAccountNumber;
    private DestinationBankAccount $destinationBankAccount;
    private string $dueDate;
    private string $amount;

    public function __construct(
        string $bankPaymentMessageType,
        string $bankPaymentMessage,
        Recipient $recipient,
        string $sourceBankAccountIban,
        DestinationBankAccount $destinationBankAccount,
        string $dueDate,
        string $amount
    ) {
        parent::__construct();
        $this->bankPaymentMessageType = $bankPaymentMessageType;
        $this->bankPaymentMessage = $bankPaymentMessage;
        $this->recipient = $recipient;
        $this->sourceBankAccountNumber = $sourceBankAccountIban;
        $this->destinationBankAccount = $destinationBankAccount;
        $this->dueDate = $dueDate;
        $this->amount = $amount;
    }

    public function getDtdPath(): string
    {
        return $this->getDtdFile('payment.dtd');
    }

    protected function getXmlName(): string
    {
        return 'payment';
    }
}
