<?php

namespace App\Application\UseCase\Empresa\Saft;

use App\Domain\Entity\Empresa\Saft\BillingAddress;
use App\Domain\Entity\Empresa\Saft\CompanyAddress;
use App\Domain\Entity\Empresa\Saft\CreditNotes;
use App\Domain\Entity\Empresa\Saft\CreditNotesLine;
use App\Domain\Entity\Empresa\Saft\Customer;
use App\Domain\Entity\Empresa\Saft\Header;
use App\Domain\Entity\Empresa\Saft\Invoice;
use App\Domain\Entity\Empresa\Saft\InvoiceLine;
use App\Domain\Entity\Empresa\Saft\MasterFiles;
use App\Domain\Entity\Empresa\Saft\Payment;
use App\Domain\Entity\Empresa\Saft\PaymentLine;
use App\Domain\Entity\Empresa\Saft\Product;
use App\Domain\Entity\Empresa\Saft\SourceDocuments;
use App\Domain\Entity\Empresa\Saft\TaxTableEntry;
use App\Domain\Entity\Empresa\Saft\WorkDocument;
use App\Models\empresa\Factura;
use App\Models\empresa\NotaCredito;
use App\Models\empresa\Recibos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class GeradorDoFicheiroSaft
{
    public function execute($mes)
    {
        $startDate = $mes . '-01 00:00';
        $date = \DateTime::createFromFormat('Y-m', $mes);
        $date->modify('last day of this month');
        $endDate = $date->format('Y-m-d') . ' 23:59';
        $now = Carbon::now()->format('Y-m-d H:i');

        $date1 = Carbon::createFromFormat('Y-m-d H:i', $endDate);
        $date2 = Carbon::createFromFormat('Y-m-d H:i', $now);
        if ($date1->gt($date2)) {
            $endDate = $now;
        }
        $empresaAdmin = DB::connection('mysql')->table('empresas')->where('id', 1)->first();
        $companyAddress = new CompanyAddress(auth()->user()->empresa->endereco, 'Luanda', 'Luanda', 'AO');

        $header = new Header(
            '1.01_01',
            auth()->user()->empresa->nif,
            auth()->user()->empresa->nif,
            'F',
            auth()->user()->empresa->nome,
            $companyAddress,
            Carbon::parse(Carbon::now())->format('Y'),
            date_format(date_create($startDate), "Y-m-d"),
            date_format(date_create($endDate), "Y-m-d"),
            'AOA',
            Carbon::parse(Carbon::now())->format('Y-m-d'),
            'Global',
            $empresaAdmin->nif,
            '384/AGT/2022',
            'Mutue-Negócios/MUTUE- SOLUÇOES TECNOLOGIA INTELIGENTES, LDA',
            '1.0.0',
            $empresaAdmin->pessoal_Contacto,
            $empresaAdmin->email,
            $empresaAdmin->website
        );
        //Start MasterFiles
        $masterFiles = new MasterFiles();
        //Customers
        $customersData = DB::table('clientes')->where('empresa_id', auth()->user()->empresa_id)->get();
        foreach ($customersData as $customer) {
            if ($customer->nif == '999999999') {
                $CustomerID = $customer->id;
                $AccountID = "Desconhecido";
                $CustomerTaxID = $customer->nif;
                $CompanyName = "Consumidor Final";
                $AddressDetail = "Desconhecido";
                $City = "Desconhecido";
                $Country = "Desconhecido";
            } else {
                $CustomerID = $customer->id;
                $AccountID = $customer->nif;
                $CustomerTaxID = $customer->nif;
                $CompanyName = $customer->nome;
                $AddressDetail = "Desconhecido";
                $City = "Luanda";
                if ($customer->endereco) {
                    $AddressDetail = $customer->endereco;
                }
                if ($customer->cidade) {
                    $City = $customer->cidade;
                }
                $Country = "AO";
            }
            $customer = new Customer(
                $CustomerID,
                $AccountID,
                $CustomerTaxID,
                $CompanyName,
                new BillingAddress(
                    $AddressDetail,
                    $City,
                    $Country
                ),
                0
            );
            $masterFiles->addCustomer($customer);
        }
        // Products
        $productsData = DB::table('produtos')->where('empresa_id', auth()->user()->empresa_id)->get();
        foreach ($productsData as $product) {
            $product = new Product('S', $product->id, 'N/A', $product->designacao, $product->id);
            $masterFiles->addProduct($product);
        }
        //Taxas IVA
        $taxTableEntriesData = DB::table('tipotaxa')->get();
        foreach ($taxTableEntriesData as $taxTableEntriy) {
            $TaxType = $taxTableEntriy->taxa > 0 ? "IVA" : "NS";
            $TaxCode = $taxTableEntriy->taxa > 0 ? "NOR" : "NS";
            $Description = $taxTableEntriy->taxa > 0 ? "Taxa Normal" : "Isenta";
            $TaxPercentage = $taxTableEntriy->taxa;
            $taxTableEntriy = new TaxTableEntry(
                $TaxType,
                $TaxCode,
                $Description,
                $TaxPercentage
            );
            $masterFiles->addTaxTableEntry($taxTableEntriy);
        }
        //End MasterFiles
        $facturaIds = DB::table('facturas')->where('empresa_id', auth()->user()->empresa_id)
            ->whereBetween(DB::raw('DATE(facturas.created_at)'), array($startDate, $endDate))
            ->where('facturas.anulado', '=', 'Y')
            ->pluck('id')->toArray();

        //Start SourcesDocuments
        $faturasData = Factura::with(['tipoDocumentoSigla', 'facturas_items', 'facturas_items.produto.motivoIsencao'])
            ->whereBetween(DB::raw('DATE(facturas.created_at)'), array($startDate, $endDate))
            ->whereIn('tipoDocumento', [1, 2])
//            ->where('anulado', '!=', 'Y')
//            ->where('retificado', '!=', 'Y')
            ->orderBy('created_at', 'asc')
            ->get();
        $notasCreditoData = NotaCredito::with(['factura', 'factura.facturas_items'])->whereNotNull('facturaId')
            ->whereIn('facturaId', $facturaIds)
            ->orderBy('created_at', 'asc')
            ->get();

        $NumberOfEntries = count($faturasData) + count($notasCreditoData);

        $TotalDebit = DB::table('notas_creditos')
            ->join('facturas', 'facturas.id', '=', 'notas_creditos.facturaId')
            ->whereBetween(DB::raw('DATE(notas_creditos.created_at)'), array($startDate, $endDate))
            ->whereNotNull('facturaId')
            ->whereIn('facturaId', $facturaIds)
            ->orderBy('created_at', 'asc')
            ->sum('facturas.valorIliquido');
        $TotalCredit = 0;
        foreach ($faturasData as $invoice) {
            $TotalCredit += $this->TotalCredit($invoice->facturas_items);
        }

       // dd($TotalCredit);


//            $TotalCredit = DB::table('facturas')
//            ->join('factura_items', 'facturas.id', '=', 'factura_items.factura_id') // Ajuste as colunas para o join
//            ->where('anulado', 'N')
//            ->where('retificado', 'N')
//            ->whereIn('tipoDocumento', [1, 2])
//            ->whereBetween(DB::raw('DATE(facturas.created_at)'), array($startDate, $endDate))
//            ->orderBy('created_at', 'asc')
//            ->selectRaw('SUM((factura_items.total - factura_items.valorDesconto)) AS total_credit')
//            ->value('total_credit');

        $sourcesDocuments = new SourceDocuments(
            $NumberOfEntries,
            $TotalDebit,
            $TotalCredit
        );
        // $TotalCredit = 0;
        foreach ($faturasData as $invoice) {
            $InvoiceStatus = $invoice->anulado == 'N' ? "N" : "A";
            $InvoiceStatusDate = Carbon::parse($invoice->created_at)->format('Y-m-d') . "T" . Carbon::parse($invoice->created_at)->format("H:i:s");
            $SourceBilling = 'P';
            $HashControl = 1;
            $Period = Carbon::parse($invoice->created_at)->format('m');
            $invoiceDate = Carbon::parse($invoice->created_at)->format('Y-m-d');
            $SelfBillingIndicator = 0;
            $CashVATSchemeIndicator = 0;
            $ThirdPartiesBillingIndicator = 0;
            $SystemEntryDate = str_replace(' ', 'T', $invoice->created_at);
            $NetTotal = $this->NetTotal($invoice->facturas_items);
            //$NetTotal = $invoice->total <= 0 ? 0 : $invoice->valorIliquido;
            $TaxPayable = $NetTotal <= 0 ? 0 : $this->TaxPayable($invoice->facturas_items);
            $GrossTotal = $TaxPayable + $NetTotal;
            $WithholdingTaxAmount = $invoice->valorRetencao;
            $PaymentAmount = ($GrossTotal - $invoice->valorRetencao);

            $invoiceEntity = new Invoice(
                $invoice->numeracaoFactura,
                $InvoiceStatus,
                $InvoiceStatusDate,
                $invoice->user_id,
                $SourceBilling,
                $invoice->hashValor,
                $HashControl,
                $Period,
                $invoiceDate,
                $invoice->tipoDocumentoSigla->sigla,
                $SelfBillingIndicator,
                $CashVATSchemeIndicator,
                $ThirdPartiesBillingIndicator,
                $SystemEntryDate,
                $invoice->clienteId,
                number_format($TaxPayable, 2, '.', ''),
                number_format($NetTotal, 2, '.', ''),
                number_format($GrossTotal, 2, '.', ''),
                number_format($WithholdingTaxAmount, 2, '.', ''),
                number_format($PaymentAmount, 2, '.', '')
            );
            foreach ($invoice->facturas_items as $key => $Line) {
                $Line = (object)$Line;
                $UnitOfMeasure = "un";
                $Description = $invoice->observacao ? $invoice->observacao : 'FACTURA ' . $invoice->numeracaoFactura;
                $TaxType = $Line->taxaIva <= 0 || $TaxPayable <= 0 ? "NS" : "IVA";
                $TaxCountryRegion = "AO";
                $TaxCode = $Line->taxaIva <= 0 || $TaxPayable <= 0 ? "NS" : "NOR";
                $TaxPercentage = $TaxPayable <= 0 ? 0 : $Line->taxaIva;
                $TaxExemptionReason = $Line->produto->motivoIsencao->descricao;
                $TaxExemptionCode = $Line->produto->motivoIsencao->codigoMotivo;
                if ($Line->valorDesconto > 0) {
                    $valorDesconto = $Line->valorDesconto;
                } else {
                    $valorDesconto = ($Line->total * $Line->desconto) / 100;
                }
                $UnitPrice = ($Line->total - $valorDesconto) / ($Line->quantidade ?? 1);
                $CreditAmount = $UnitPrice * $Line->quantidade;
                // $TotalCredit += $CreditAmount;
                $invoiceLine = new InvoiceLine(
                    ++$key,
                    $Line->produtoId,
                    $Line->nomeProduto,
                    number_format($Line->quantidade, 1, '.', ''),
                    $UnitOfMeasure,
                    $UnitPrice < 0 ? 0 : $UnitPrice,
                    Carbon::parse($invoice->created_at)->format('Y-m-d'),
                    $Description,
                    number_format($CreditAmount < 0 ? 0 : $CreditAmount, 2, ".", ""),
                    $TaxType,
                    $TaxCountryRegion,
                    $TaxCode,
                    $TaxPercentage,
                    $TaxExemptionReason,
                    $TaxExemptionCode,
                    $Line->valorDesconto
                );
                $invoiceEntity->addInvoiceLine($invoiceLine);
            }
            $sourcesDocuments->addInvoice($invoiceEntity);
        }


        foreach ($notasCreditoData as $invoice) {
            $InvoiceStatus = "N";
            $InvoiceStatusDate = Carbon::parse($invoice->created_at)->format('Y-m-d') . "T" . Carbon::parse($invoice->created_at)->format("H:i:s");
            $SourceBilling = 'P';
            $HashControl = 1;
            $Period = Carbon::parse($invoice->created_at)->format('m');
            $invoiceDate = Carbon::parse($invoice->created_at)->format('Y-m-d');
            $SelfBillingIndicator = 0;
            $CashVATSchemeIndicator = 0;
            $ThirdPartiesBillingIndicator = 0;
            $SystemEntryDate = str_replace(' ', 'T', $invoice->created_at);
            $InvoiceType = "NC";
            $Desconto = 0;
            $TaxPayable = $invoice->factura->valorImposto - $Desconto;
            $NetTotal = $invoice->factura->valorIliquido;
            $GrossTotal = $TaxPayable + $NetTotal;

            $invoiceEntity = new CreditNotes(
                $invoice->numDoc,
                $InvoiceStatus,
                $InvoiceStatusDate,
                $invoice->user_id,
                $SourceBilling,
                $invoice->hashValor,
                $HashControl,
                $Period,
                $invoiceDate,
                $InvoiceType,
                $SelfBillingIndicator,
                $CashVATSchemeIndicator,
                $ThirdPartiesBillingIndicator,
                $SystemEntryDate,
                $invoice->factura->clienteId,
                number_format($TaxPayable, 2, '.', ''),
                number_format($NetTotal, 2, '.', ''),
                number_format($GrossTotal, 2, '.', '')
            );
            $sourcesDocuments->addInvoice($invoiceEntity);
            foreach ($invoice->factura->facturas_items as $key => $Line) {
                $Line = (object)$Line;
                $UnitOfMeasure = "un";
                $TaxType = $Line->taxaIva <= 0 || $TaxPayable <= 0 ? "NS" : "IVA";
                $TaxCountryRegion = "AO";
                $TaxCode = $Line->taxaIva <= 0 || $TaxPayable <= 0 ? "NS" : "NOR";
                $TaxPercentage = $TaxPayable <= 0 ? 0 : $Line->taxaIva;
                $TaxExemptionReason = $Line->produto->motivoIsencao->descricao;
                $TaxExemptionCode = $Line->produto->motivoIsencao->codigoMotivo;
                $Reason = $invoice->descricao ?? "Anulação do documento " . $invoice->factura->numeracaoFactura;
                $UnitPrice = ($Line->total - $Line->valorDesconto) / ($Line->quantidade ?? 1);

                $invoiceLine = new CreditNotesLine(
                    ++$key,
                    $Line->produtoId,
                    $Line->nomeProduto,
                    number_format($Line->quantidade, 1, '.', ''),
                    $UnitOfMeasure,
                    $UnitPrice,
                    Carbon::parse($invoice->created_at)->format('Y-m-d'),
                    $invoice->factura->numeracaoFactura,
                    $Reason,
                    $Line->nomeProduto,
                    number_format($Line->total, 2, ".", ""),
                    $TaxType,
                    $TaxCountryRegion,
                    $TaxCode,
                    $TaxPercentage,
                    $TaxExemptionReason,
                    $TaxExemptionCode,
                    $Line->desconto
                );
                $invoiceEntity->addInvoiceLine($invoiceLine);
            }
        }
        //start WorkDocuments
        $faturasProformaData = Factura::with(['tipoDocumentoSigla', 'facturas_items', 'facturas_items.produto.motivoIsencao'])
            ->orderBy('created_at', 'asc')
            ->whereBetween(DB::raw('DATE(facturas.created_at)'), array($startDate, $endDate))
            ->where('tipoDocumento', 3)
            ->get();
        $WorkTotalDebit = 0;


//        $WorkTotalCredit = DB::table('facturas')
//            ->join('factura_items', 'facturas.id', '=', 'factura_items.factura_id') // Ajuste as colunas para o join
//            ->where('anulado', 'N')
//            ->where('retificado', 'N')
//            ->whereIn('tipoDocumento', 3)
//            ->whereBetween(DB::raw('DATE(facturas.created_at)'), array($startDate, $endDate))
//            ->orderBy('created_at', 'asc')
//            ->selectRaw('SUM((factura_items.total - factura_items.valorDesconto)) AS total_credit')
//            ->value('total_credit');


        $WorkTotalCredit = DB::table('facturas')
            ->join('factura_items', 'facturas.id', '=', 'factura_items.factura_id')
            ->where('anulado', 'N')
            ->where('retificado', 'N')
            ->whereBetween(DB::raw('DATE(facturas.created_at)'), array($startDate, $endDate))
            ->where('tipoDocumento', 3)
            ->orderBy('created_at', 'asc')
            ->selectRaw('SUM((factura_items.total - factura_items.valorDesconto)) AS total_credit')
            ->value('total_credit');

        $WorkNumberOfEntries = count($faturasProformaData);
        $sourcesDocuments->setHeaderWorkDocument(
            $WorkNumberOfEntries,
            $WorkTotalDebit,
            $WorkTotalCredit ?? 0);

        foreach ($faturasProformaData as $invoice) {
            $WorkStatus = $invoice->anulado == "N" ? "N" : "A";
            $InvoiceStatusDate = Carbon::parse($invoice->created_at)->format('Y-m-d') . "T" . Carbon::parse($invoice->created_at)->format("H:i:s");
            $SourceBilling = 'P';
            $HashControl = 1;
            $Period = Carbon::parse($invoice->created_at)->format('m');
            $SystemEntryDate = Carbon::parse($invoice->created_at)->format('Y-m-d') . "T" . Carbon::parse($invoice->created_at)->format("H:i:s");
            $Desconto = 0;
            $invoiceDate = Carbon::parse($invoice->created_at)->format('Y-m-d');
            $TaxPayable = $invoice->valorImposto - $Desconto;
            $NetTotal = $invoice->valorIliquido;
            $GrossTotal = $TaxPayable + $NetTotal;
            $DocumentNumber = str_replace("FP", "PP", $invoice->numeracaoFactura);
            $workDocument = new WorkDocument(
                $DocumentNumber,
                $WorkStatus,
                $InvoiceStatusDate,
                $invoice->user_id,
                $SourceBilling,
                $invoice->hashValor,
                $HashControl,
                $Period,
                $invoiceDate,
                'PP',
                $SystemEntryDate,
                '',
                $invoice->clienteId,
                number_format($TaxPayable, 2, '.', ''),
                number_format($NetTotal, 2, '.', ''),
                number_format($GrossTotal, 2, '.', '')
            );
            $sourcesDocuments->addWorkDocument($workDocument);
            foreach ($invoice->facturas_items as $key => $Line) {
                $Line = (object)$Line;
                $UnitOfMeasure = "un";
                $Description = $invoice->observacao ? $invoice->observacao : 'FACTURA ' . $invoice->numeracaoFactura;
                $TaxType = $Line->taxaIva <= 0 || $TaxPayable <= 0 ? "NS" : "IVA";
                $TaxCountryRegion = "AO";
                $TaxCode = $Line->taxaIva <= 0 || $TaxPayable <= 0 ? "NS" : "NOR";
                $TaxPercentage = $TaxPayable <= 0 ? 0 : $Line->taxaIva;
                $TaxExemptionReason = $Line->produto->motivoIsencao->descricao;
                $TaxExemptionCode = $Line->produto->motivoIsencao->codigoMotivo;
                $UnitPrice = ($Line->total - $Line->valorDesconto) / ($Line->quantidade ?? 1);
                $CreditAmount = $UnitPrice;
                $invoiceLine = new InvoiceLine(
                    ++$key,
                    $Line->produtoId,
                    $Line->nomeProduto,
                    number_format($Line->quantidade, 1, '.', ''),
                    $UnitOfMeasure,
                    $UnitPrice,
                    Carbon::parse($invoice->created_at)->format('Y-m-d'),
                    $Description,
                    $CreditAmount * $Line->quantidade,
                    $TaxType,
                    $TaxCountryRegion,
                    $TaxCode,
                    $TaxPercentage,
                    $TaxExemptionReason,
                    $TaxExemptionCode,
                    $Line->desconto
                );
                $workDocument->addInvoiceLine($invoiceLine);
            }
        }
        //end WorkDocuments

        //start Payments
        $recibosData = Recibos::with(['factura'])
            ->where('empresaId', auth()->user()->empresa_id)
            ->whereBetween(DB::raw('DATE(recibos.created_at)'), array($startDate, $endDate))
            ->orderBy('created_at', 'asc')
            ->get();

        $PaymentNumberOfEntries = count($recibosData);
        $PaymentTotalDebit = 0;
        $PaymentTotalCredit = DB::table('recibos')
            ->where('anulado', "N") //recibo não anulado
            ->where('empresaId', auth()->user()->empresa_id)
            ->whereBetween(DB::raw('DATE(recibos.created_at)'), array($startDate, $endDate))
            ->orderBy('created_at', 'asc')
            ->sum('totalEntregue');

        $sourcesDocuments->setHeaderPaymentDocument(
            $PaymentNumberOfEntries,
            $PaymentTotalDebit,
            $PaymentTotalCredit);

        foreach ($recibosData as $payment) {

            $Period = Carbon::parse($payment->created_at)->format('m');
            $TransactionDate = Carbon::parse($payment->created_at)->format('Y-m-d');
            $PaymentType = "RC";
            $PaymentStatus = $payment->anulado == "N" ? "N" : "A";
            $PaymentStatusDate = Carbon::parse($payment->created_at)->format('Y-m-d') . "T" . Carbon::parse($payment->created_at)->format("H:i:s");
            $SourcePayment = 'P';
            $PaymentDate = Carbon::parse($payment->created_at)->format('Y-m-d');
            $SystemEntryDate = Carbon::parse($payment->created_at)->format('Y-m-d') . "T" . Carbon::parse($payment->created_at)->format("H:i:s");

            $TaxPayable = 0;
            $NetTotal = $payment->totalEntregue;
            $GrossTotal = $payment->totalEntregue;

            $paymentEntity = new Payment(
                $payment->numeracaoRecibo,
                $Period,
                $TransactionDate,
                $PaymentType,
                $payment->userId,
                $PaymentStatus,
                $PaymentStatusDate,
                $payment->userId,
                $SourcePayment,
                $payment->totalEntregue,
                $PaymentDate,
                $SystemEntryDate,
                $payment->clienteId,
                $TaxPayable,
                $NetTotal,
                $GrossTotal
            );
            $sourcesDocuments->addPayment($paymentEntity);
            $LineNumer = 1;
            $SettlementAmount = 0;
            $paymentLine = new PaymentLine(
                $LineNumer,
                $payment->factura->numeracaoFactura,
                Carbon::parse($payment->factura->created_at)->format('Y-m-d'),
                $SettlementAmount,
                $GrossTotal
            );
            $paymentEntity->addPaymentLine($paymentLine);
        }
        //end Payments
        //end SourcesDocuments
        $header->addMasterFile($masterFiles);
        $header->addSourceDocument($sourcesDocuments);
//        dd($header->toXML());
        $xmlString = $header->toXML();

        $fileName = 'xml_' . time() . '.xml';

        // Salvar o conteúdo XML em um arquivo temporário
        $filePath = storage_path('app/' . $fileName);
        file_put_contents($filePath, $xmlString);
        // Retornar o arquivo XML para download
        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    private function TotalCredit($items){
        $TotalCredit = 0;
        foreach ($items as $Line){
            if ($Line->valorDesconto > 0) {
                $valorDesconto = $Line->valorDesconto;
            } else {
                $valorDesconto = ($Line->total * $Line->desconto) / 100;
            }
            $UnitPrice = ($Line->total - $valorDesconto) / ($Line->quantidade ?? 1);
            $TotalCredit += $UnitPrice * $Line->quantidade;

        }
        return $TotalCredit;
    }

    private function TaxPayable($items)
    {
        $TaxPayable = 0;
        foreach ($items as $Line) {
            if ($Line->valorDesconto > 0) {
                $desconto = $Line->valorDesconto;
            } else {
                $desconto = ($Line->total * $Line->desconto) / 100;
            }
            $total = $Line->total - $desconto;
            $TaxPayable += ($total * $Line->taxaIva) / 100;
        }
        return $TaxPayable;
    }

    private function NetTotal($items)
    {
        $NetTotal = 0;
        foreach ($items as $Line) {
            if ($Line->valorDesconto > 0) {
                $valorDesconto = $Line->valorDesconto;
            } else {
                $valorDesconto = ($Line->total * $Line->desconto) / 100;
            }
            $UnitPrice = ($Line->total - $valorDesconto) / ($Line->quantidade ?? 1);
            $NetTotal += $UnitPrice * $Line->quantidade;
        }
        return $NetTotal;
    }

}
