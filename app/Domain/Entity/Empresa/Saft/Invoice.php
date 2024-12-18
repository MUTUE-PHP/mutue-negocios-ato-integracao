<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class Invoice
{
    private $InvoiceNo;
    private $InvoiceStatus;
    private $InvoiceStatusDate;
    private $SourceID;
    private $SourceBilling;
    private $Hash;
    private $HashControl;
    private $Period;
    private $InvoiceDate;
    private $InvoiceType;
    private $SelfBillingIndicator;
    private $CashVATSchemeIndicator;
    private $ThirdPartiesBillingIndicator;
    private $SystemEntryDate;
    private $CustomerID;
    private $TaxPayable;
    private $NetTotal;
    private $GrossTotal;
    private $PaymentAmount;
    private $WithholdingTaxAmount;
    private $invoiceLines = [];

    public function __construct($InvoiceNo, $InvoiceStatus, $InvoiceStatusDate, $SourceID, $SourceBilling, $Hash, $HashControl, $Period, $InvoiceDate, $InvoiceType, $SelfBillingIndicator, $CashVATSchemeIndicator, $ThirdPartiesBillingIndicator, $SystemEntryDate, $CustomerID, $TaxPayable, $NetTotal, $GrossTotal,$WithholdingTaxAmount, $PaymentAmount)
    {
        $this->InvoiceNo = $InvoiceNo;
        $this->InvoiceStatus = $InvoiceStatus;
        $this->InvoiceStatusDate = $InvoiceStatusDate;
        $this->SourceID = $SourceID;
        $this->SourceBilling = $SourceBilling;
        $this->Hash = $Hash;
        $this->HashControl = $HashControl;
        $this->Period = $Period;
        $this->InvoiceDate = $InvoiceDate;
        $this->InvoiceType = $InvoiceType;
        $this->SelfBillingIndicator = $SelfBillingIndicator;
        $this->CashVATSchemeIndicator = $CashVATSchemeIndicator;
        $this->ThirdPartiesBillingIndicator = $ThirdPartiesBillingIndicator;
        $this->SystemEntryDate = $SystemEntryDate;
        $this->CustomerID = $CustomerID;
        $this->TaxPayable = $TaxPayable;
        $this->NetTotal = $NetTotal;
        $this->GrossTotal = $GrossTotal;
        $this->WithholdingTaxAmount = $WithholdingTaxAmount;
        $this->PaymentAmount = $PaymentAmount;
    }

    public function addInvoiceLine($invoiceLine)
    {
        $this->invoiceLines[] = $invoiceLine;
    }

    public function toXML()
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);


        $xmlWriter->startElement('Invoice');

        $xmlWriter->startElement('InvoiceNo');
        $xmlWriter->text($this->InvoiceNo);
        $xmlWriter->endElement();

        $xmlWriter->startElement('DocumentStatus');

        $xmlWriter->startElement('InvoiceStatus');
        $xmlWriter->text($this->InvoiceStatus);
        $xmlWriter->endElement();

        $xmlWriter->startElement('InvoiceStatusDate');
        $xmlWriter->text($this->InvoiceStatusDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SourceID');
        $xmlWriter->text($this->SourceID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SourceBilling');
        $xmlWriter->text($this->SourceBilling);
        $xmlWriter->endElement();
        $xmlWriter->endElement();//End DocumentStatus

        $xmlWriter->startElement('Hash');
        $xmlWriter->text($this->Hash);
        $xmlWriter->endElement();

        $xmlWriter->startElement('HashControl');
        $xmlWriter->text($this->HashControl);
        $xmlWriter->endElement();

        $xmlWriter->startElement('Period');
        $xmlWriter->text($this->Period);
        $xmlWriter->endElement();

        $xmlWriter->startElement('InvoiceDate');
        $xmlWriter->text($this->InvoiceDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('InvoiceType');
        $xmlWriter->text($this->InvoiceType);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SpecialRegimes');

        $xmlWriter->startElement('SelfBillingIndicator');
        $xmlWriter->text($this->SelfBillingIndicator);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CashVATSchemeIndicator');
        $xmlWriter->text($this->CashVATSchemeIndicator);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ThirdPartiesBillingIndicator');
        $xmlWriter->text($this->ThirdPartiesBillingIndicator);
        $xmlWriter->endElement();
        $xmlWriter->endElement();//End SpecialRegimes


        $xmlWriter->startElement('SourceID');
        $xmlWriter->text($this->SourceID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('SystemEntryDate');
        $xmlWriter->text($this->SystemEntryDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CustomerID');
        $xmlWriter->text($this->CustomerID);
        $xmlWriter->endElement();

        foreach ($this->invoiceLines as $invoiceLine) {
            $xmlWriter->writeRaw($invoiceLine->toXML($xmlWriter));
        }

        $xmlWriter->startElement('DocumentTotals');

        $xmlWriter->startElement('TaxPayable');
        $xmlWriter->text($this->TaxPayable);
        $xmlWriter->endElement();

        $xmlWriter->startElement('NetTotal');
        $xmlWriter->text($this->NetTotal);
        $xmlWriter->endElement();

        $xmlWriter->startElement('GrossTotal');
        $xmlWriter->text($this->GrossTotal);
        $xmlWriter->endElement();

        if($this->InvoiceType == "FR"){
            $PaymentMechanism = "NU";
        }else if($this->InvoiceType == "FT"){
            $PaymentMechanism = "CC";
        }else{
            $PaymentMechanism = "OU";
        }
        $xmlWriter->startElement('Payment');
            $xmlWriter->startElement('PaymentMechanism');
            $xmlWriter->text($PaymentMechanism);
            $xmlWriter->endElement();

            $xmlWriter->startElement('PaymentAmount');
            $xmlWriter->text($this->PaymentAmount);
            $xmlWriter->endElement();

            $xmlWriter->startElement('PaymentDate');
            $xmlWriter->text($this->InvoiceDate);
            $xmlWriter->endElement();
        $xmlWriter->endElement();//End Payment

        $xmlWriter->endElement();//End DocumentTotals
        $xmlWriter->startElement('WithholdingTax');
            $xmlWriter->startElement('WithholdingTaxType');
            $xmlWriter->text('II');
            $xmlWriter->endElement();

            $xmlWriter->startElement('WithholdingTaxDescription');
            $xmlWriter->text('Aplicação da Retenção');
            $xmlWriter->endElement();

            $xmlWriter->startElement('WithholdingTaxAmount');
            $xmlWriter->text($this->WithholdingTaxAmount);
            $xmlWriter->endElement();
        $xmlWriter->endElement();//End WithholdingTax
        $xmlWriter->endElement();//End Invoice
        return $xmlWriter->outputMemory(true);

    }


}
