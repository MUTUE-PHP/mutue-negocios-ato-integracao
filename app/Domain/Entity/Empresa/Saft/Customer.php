<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class Customer
{
    private $customerID;
    private $accountID;
    private $customerTaxID;
    private $companyName;
    private BillingAddress $billingAddress;
    private $selfBillingIndicator = 0;

    public function __construct($customerID, $accountID, $customerTaxID, $companyName, BillingAddress $billingAddress, int $selfBillingIndicator = 0)
    {
        $this->customerID = $customerID;
        $this->accountID = $accountID;
        $this->customerTaxID = $customerTaxID;
        $this->companyName = $companyName;
        $this->billingAddress = $billingAddress;
        $this->selfBillingIndicator = $selfBillingIndicator;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);


        $xmlWriter->startElement('Customer');
        $xmlWriter->startElement('CustomerID');
        $xmlWriter->text($this->customerID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('AccountID');
        $xmlWriter->text($this->accountID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CustomerTaxID');
        $xmlWriter->text("999999999");
//        $xmlWriter->text($this->customerTaxID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CompanyName');
        $xmlWriter->text($this->companyName);
        $xmlWriter->endElement();
        $xmlWriter->writeRaw($this->billingAddress->toXML());

        $xmlWriter->startElement('SelfBillingIndicator');
        $xmlWriter->text($this->selfBillingIndicator);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory(true);
    }


}
