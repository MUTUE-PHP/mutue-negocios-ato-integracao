<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class Header
{
    private $auditFileVersion;
    private $companyID;
    private $taxRegistrationNumber;
    private $taxAccountingBasis;
    private $companyName;
    private CompanyAddress $companyAddress;
    private $fiscalYear;
    private $startDate;
    private $endDate;
    private $currencyCode;
    private $dateCreated;
    private $taxEntity;
    private $productCompanyTaxID;
    private $softwareValidationNumber;
    private $productID;
    private $productVersion;
    private $telephone;
    private $email;
    private $website;

    private $masterFile;
    private $sourceDocument;

    public function __construct($auditFileVersion, $companyID, $taxRegistrationNumber, $taxAccountingBasis, $companyName, CompanyAddress $companyAddress, $fiscalYear, $startDate, $endDate, $currencyCode, $dateCreated, $taxEntity, $productCompanyTaxID, $softwareValidationNumber, $productID, $productVersion, $telephone, $email, $website)
    {
        $this->auditFileVersion = $auditFileVersion;
        $this->companyID = $companyID;
        $this->taxRegistrationNumber = $taxRegistrationNumber;
        $this->taxAccountingBasis = $taxAccountingBasis;
        $this->companyName = $companyName;
        $this->companyAddress = $companyAddress;
        $this->fiscalYear = $fiscalYear;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->currencyCode = $currencyCode;
        $this->dateCreated = $dateCreated;
        $this->taxEntity = $taxEntity;
        $this->productCompanyTaxID = $productCompanyTaxID;
        $this->softwareValidationNumber = $softwareValidationNumber;
        $this->productID = $productID;
        $this->productVersion = $productVersion;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->website = $website;
    }
    public function addMasterFile($masterFile){
        $this->masterFile = $masterFile;
    }
    public function addSourceDocument($sourceDocument){
        $this->sourceDocument = $sourceDocument;
    }


    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);

        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElementNS(null, 'AuditFile', 'urn:OECD:StandardAuditFile-Tax:PT_1.04_01');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');

        $xmlWriter->startElement('Header');

        $xmlWriter->startElement('AuditFileVersion');
        $xmlWriter->text($this->auditFileVersion);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CompanyID');
        $xmlWriter->text($this->companyID);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxRegistrationNumber');
        $xmlWriter->text($this->taxRegistrationNumber);
        $xmlWriter->endElement();

        $xmlWriter->startElement('TaxAccountingBasis');
        $xmlWriter->text($this->taxAccountingBasis);
        $xmlWriter->endElement();

        $xmlWriter->startElement('CompanyName');
        $xmlWriter->text($this->companyName);
        $xmlWriter->endElement();

        $this->companyAddress->toXML($xmlWriter);

        $xmlWriter->startElement('FiscalYear');
        $xmlWriter->text($this->fiscalYear);
        $xmlWriter->endElement();

        $xmlWriter->startElement('StartDate');
        $xmlWriter->text($this->startDate);
        $xmlWriter->endElement();

        $xmlWriter->startElement('EndDate');
        $xmlWriter->text($this->endDate);
        $xmlWriter->endElement();
        $xmlWriter->startElement('CurrencyCode');
        $xmlWriter->text($this->currencyCode);
        $xmlWriter->endElement();
        $xmlWriter->startElement('DateCreated');
        $xmlWriter->text($this->dateCreated);
        $xmlWriter->endElement();
        $xmlWriter->startElement('TaxEntity');
        $xmlWriter->text($this->taxEntity);
        $xmlWriter->endElement();
        $xmlWriter->startElement('ProductCompanyTaxID');
        $xmlWriter->text($this->productCompanyTaxID);
        $xmlWriter->endElement();
        $xmlWriter->startElement('SoftwareValidationNumber');
        $xmlWriter->text($this->softwareValidationNumber);
        $xmlWriter->endElement();
        $xmlWriter->startElement('ProductID');
        $xmlWriter->text($this->productID);
        $xmlWriter->endElement();
        $xmlWriter->startElement('ProductVersion');
        $xmlWriter->text($this->productVersion);
        $xmlWriter->endElement();
        $xmlWriter->startElement('Telephone');
        $xmlWriter->text($this->telephone);
        $xmlWriter->endElement();
        $xmlWriter->startElement('Email');
        $xmlWriter->text($this->email);
        $xmlWriter->endElement();
        $xmlWriter->startElement('Website');
        $xmlWriter->text($this->website);
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // Header

        $xmlWriter->writeRaw($this->masterFile->toXML());
        $xmlWriter->writeRaw($this->sourceDocument->toXML());

        $xmlWriter->endElement(); // AuditFile
        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory(true);
    }

}
