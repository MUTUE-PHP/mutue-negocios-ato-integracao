<?php

namespace App\Domain\Entity\Empresa\Saft;

class Supplier
{
    private $supplierID;
    private $accountID;
    private $supplierTaxID;
    private $companyName;

    private BillingAddress $billingAddress;
    private $selfBillingIndicator = 0;


    public function __construct($supplierID, $accountID, $supplierTaxID, $companyName, BillingAddress $billingAddress, int $selfBillingIndicator = 0)
    {
        $this->supplierID = $supplierID;
        $this->accountID = $accountID;
        $this->supplierTaxID = $supplierTaxID;
        $this->companyName = $companyName;
        $this->billingAddress = $billingAddress;
        $this->selfBillingIndicator = $selfBillingIndicator;
    }


}
