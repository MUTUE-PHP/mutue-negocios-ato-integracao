<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class SourceDocuments
{
    private $NumberOfEntries;
    private $TotalDebit;
    private $TotalCredit;
    private $invoices = [];
    private $workDocuments = [];
    private $payments = [];
    private $creditNotes = [];

    public function __construct($NumberOfEntries, $TotalDebit, $TotalCredit)
    {
        $this->NumberOfEntries = $NumberOfEntries;
        $this->TotalDebit = $TotalDebit;
        $this->TotalCredit = $TotalCredit;
    }
    public function setHeaderWorkDocument($NumberOfEntries, $TotalDebit, $TotalCredit){
        $this->WorkNumberOfEntries = $NumberOfEntries;
        $this->WorkTotalDebit = $TotalDebit;
        $this->WorkTotalCredit = $TotalCredit;
    }
    public function setHeaderPaymentDocument($NumberOfEntries, $TotalDebit, $TotalCredit){
        $this->PaymentNumberOfEntries = $NumberOfEntries;
        $this->PaymentTotalDebit = $TotalDebit;
        $this->PaymentTotalCredit = $TotalCredit;
    }




    public function addInvoice($invoice)
    {
        $this->invoices[] = $invoice;
    }
    public function addcreditNote($creditNote){
        $this->creditNotes[] = $creditNote;
    }
    public function addWorkDocument($workDocument){
        $this->workDocuments[] = $workDocument;
    }
    public function addPayment($payment){
        $this->payments[] = $payment;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startElement('SourceDocuments');

        $xmlWriter->startElement('SalesInvoices');

        $xmlWriter->startElement('NumberOfEntries');
        $xmlWriter->text($this->NumberOfEntries);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TotalDebit');
        $xmlWriter->text($this->TotalDebit);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TotalCredit');
        $xmlWriter->text($this->TotalCredit);
        $xmlWriter->endElement();

        foreach ($this->invoices as $invoice) {
            $xmlWriter->writeRaw($invoice->toXML($xmlWriter));
        }
        foreach ($this->creditNotes as $creditNote) {
            $xmlWriter->writeRaw($creditNote->toXML($xmlWriter));
        }
        $xmlWriter->endElement();//End SalesInvoices

        $xmlWriter->startElement('WorkingDocuments');
        $xmlWriter->startElement('NumberOfEntries');
        $xmlWriter->text($this->WorkNumberOfEntries);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TotalDebit');
        $xmlWriter->text($this->WorkTotalDebit);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TotalCredit');
        $xmlWriter->text($this->WorkTotalCredit);
        $xmlWriter->endElement();

        foreach ($this->workDocuments as $workDocument){
            $xmlWriter->writeRaw($workDocument->toXML($xmlWriter));
        }
        $xmlWriter->endElement();//End WorkingDocuments

        $xmlWriter->startElement('Payments');

        $xmlWriter->startElement('NumberOfEntries');
        $xmlWriter->text($this->PaymentNumberOfEntries);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TotalDebit');
        $xmlWriter->text($this->PaymentTotalDebit);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TotalCredit');
        $xmlWriter->text($this->PaymentTotalCredit);
        $xmlWriter->endElement();

        foreach ($this->payments as $payment){
            $xmlWriter->writeRaw($payment->toXML($xmlWriter));
        }
        $xmlWriter->endElement();//End Payments

        $xmlWriter->endElement();//End SourceDocuments
        return $xmlWriter->outputMemory(true);
    }
}
