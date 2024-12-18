<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class Product
{
    private $productType;
    private $productCode;
    private $productGroup;
    private $productDescription;
    private $productNumberCode;

    public function __construct($productType, $productCode, $productGroup, $productDescription, $productNumberCode)
    {
        $this->productType = $productType;
        $this->productCode = $productCode;
        $this->productGroup = $productGroup;
        $this->productDescription = $productDescription;
        $this->productNumberCode = $productNumberCode;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);


        $xmlWriter->startElement('Product');
        $xmlWriter->startElement('ProductType');
        $xmlWriter->text($this->productType);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ProductCode');
        $xmlWriter->text($this->productCode);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ProductGroup');
        $xmlWriter->text($this->productGroup);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ProductDescription');
        $xmlWriter->text($this->productDescription);
        $xmlWriter->endElement();

        $xmlWriter->startElement('ProductNumberCode');
        $xmlWriter->text($this->productNumberCode);
        $xmlWriter->endElement();

        $xmlWriter->endElement();
        $xmlWriter->endDocument();

        return $xmlWriter->outputMemory(true);
    }



}
