<?php

namespace App\Domain\Entity\Empresa\Saft;

use PhpOffice\PhpSpreadsheet\Shared\XMLWriter;

class MasterFiles
{
    private $customers = [];
    private $suppliers = [];
    private $products = [];
    private $taxTableEntries = [];



    public function addCustomer($customer){
        $this->customers[] = $customer;
    }
    public function addSupplier($supplier){
        $this->suppliers[] = $supplier;
    }
    public function addProduct($product){
        $this->products[] = $product;
    }
    public function addTaxTableEntry($taxTableEntry){
        $this->taxTableEntries[] = $taxTableEntry;
    }
    public function toXML() {
        $xmlWriter = new XMLWriter();
        $xmlWriter->startElement('MasterFiles');

        foreach ($this->customers as $customer) {
            $xmlWriter->writeRaw($customer->toXML($xmlWriter));
        }
//        foreach ($this->suppliers as $supplier) {
//            $supplier->toXML($xmlWriter);
//        }
        foreach ($this->products as $product) {
            $xmlWriter->writeRaw($product->toXML());
        }
        $xmlWriter->startElement('TaxTable');
        foreach ($this->taxTableEntries as $taxTableEntry) {
            $xmlWriter->writeRaw($taxTableEntry->toXML());
        }
        $xmlWriter->endElement(); //TaxTable
        $xmlWriter->endElement(); // MasterFiles
        return $xmlWriter->outputMemory(true);
    }
}
