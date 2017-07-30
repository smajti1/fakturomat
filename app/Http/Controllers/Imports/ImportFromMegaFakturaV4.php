<?php

namespace App\Http\Controllers\Imports;

use App\Helpers\SimpleCsv;
use App\Models\Buyer;
use App\Models\Importer\DataImportedBuyer;
use App\Models\Importer\DataImportedProduct;
use App\Models\Importer\DataImporter;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportFromMegaFakturaV4
{

    protected $simpleCsv;
    protected $dataImporter;
    public $vatId = [
        1 => 23,
        2 => 8,
        3 => 5,
        4 => 22,
        5 => 7,
        6 => 3,
        7 => 0,
        8 => 'zwolniony',
        9 => 'nie podlega',
    ];

    public function __construct(SimpleCsv $simpleCsv)
    {
        $this->simpleCsv = $simpleCsv;
        $this->dataImporter = DataImporter::where('name', DataImporter::MEGA_FAKTURKA_NAME)->firstOrFail();
    }

    public function import()
    {
        $productsCsv = $this->simpleCsv->fileFromRequest('products')->readData();
        $buyersCsv = $this->simpleCsv->new()->fileFromRequest('buyers')->readData();

        $filteredProductsCsv = $this->filterImportedProducts($productsCsv);
        $filteredBuyersCsv = $this->filterImportedBuyers($buyersCsv);

        DB::transaction(function () use ($filteredProductsCsv, $filteredBuyersCsv) {
            $this->importProducts($filteredProductsCsv);
            $this->importBuyers($filteredBuyersCsv);
        });

    }

    private function importProducts(SimpleCsv $productsCsv)
    {
        $products = [];
        foreach ($productsCsv->getLines() as $product) {
            $tmpProduct = new Product;
            $tmpProduct->name = trim($product[$productsCsv->getHeaders()['nazwa']]);
            $measure_unit = trim($product[$productsCsv->getHeaders()['jm']]);
            $measure_unit = preg_replace('/[0-9]/', '', $measure_unit);
            $tmpProduct->measure_unit = $measure_unit;
            $tmpProduct->price = $product[$productsCsv->getHeaders()['cena']];
            $vat = $product[$productsCsv->getHeaders()['vat']];
            $tmpProduct->tax_percent = $this->vatId[$vat];

            $oldId = $product[$productsCsv->getHeaders()['id']];
            $tmpProduct->user()->associate(Auth::user());
            $tmpProduct->save();
            $dataImporterProduct = new DataImportedProduct();
            $dataImporterProduct->product()->associate($tmpProduct);
            $dataImporterProduct->dataImporter()->associate($this->dataImporter);
            $dataImporterProduct->imported_id = $oldId;
            $dataImporterProduct->save();
            $products[$oldId] = $tmpProduct;
        }

        return $products;
    }

    private function importBuyers(SimpleCsv $buyersCsv)
    {
        $buyers = [];
        foreach ($buyersCsv->getLines() as $product) {
            $tmpBuyer = new Buyer;
            $tmpBuyer->name = trim($product[$buyersCsv->getHeaders()['nazwa']]);
            $tmpBuyer->city = trim($product[$buyersCsv->getHeaders()['miejscowosc']]);
            $tmpBuyer->zip_code = trim($product[$buyersCsv->getHeaders()['kodpoczt']]);
            $tmpBuyer->street = trim($product[$buyersCsv->getHeaders()['ulica']]);
            $tmpBuyer->tax_id_number = trim($product[$buyersCsv->getHeaders()['nip']]);
            $tmpBuyer->regon = trim($product[$buyersCsv->getHeaders()['regon']]);
            $tmpBuyer->email = trim($product[$buyersCsv->getHeaders()['email']]);
            $tmpBuyer->phone = trim($product[$buyersCsv->getHeaders()['tel']]);

            $oldId = $product[$buyersCsv->getHeaders()['id']];
            $tmpBuyer->user()->associate(Auth::user());
            $tmpBuyer->save();
            $dataImporterBuyer = new DataImportedBuyer();
            $dataImporterBuyer->buyer()->associate($tmpBuyer);
            $dataImporterBuyer->dataImporter()->associate($this->dataImporter);
            $dataImporterBuyer->imported_id = $oldId;
            $dataImporterBuyer->save();
            $buyers[$oldId] = $tmpBuyer;
        }

        return $buyers;
    }

    private function filterImportedProducts(SimpleCsv $productsCsv)
    {
        $filteredProductsCsv = $productsCsv->copy();
        $importedIdList = [];
        $importedIdMapByCsvKey = [];
        foreach ($filteredProductsCsv->getLines() as $csvKey => $product) {
            $tmpImportedId = $product[$filteredProductsCsv->getHeaders()['id']];
            $importedIdList[] = $tmpImportedId;
            $importedIdMapByCsvKey[$product[$filteredProductsCsv->getHeaders()['id']]] = $csvKey;
        }
        $already_imported = $this->dataImporter->dataImportedProducts()
            ->whereIn('imported_id', $importedIdList)
            ->get();
        foreach ($already_imported as $item) {
            $csvKey = $importedIdMapByCsvKey[$item->imported_id];
            $filteredProductsCsv->unset($csvKey);
        }

        return $filteredProductsCsv;
    }

    private function filterImportedBuyers(SimpleCsv $buyersCsv)
    {
        $filteredBuyersCsv = $buyersCsv->copy();
        $importedIdList = [];
        $importedIdMapByCsvKey = [];
        foreach ($filteredBuyersCsv->getLines() as $csvKey => $product) {
            $tmpImportedId = $product[$filteredBuyersCsv->getHeaders()['id']];
            $importedIdList[] = $tmpImportedId;
            $importedIdMapByCsvKey[$product[$filteredBuyersCsv->getHeaders()['id']]] = $csvKey;
        }
        $already_imported = $this->dataImporter->dataImportedBuyers()
            ->whereIn('imported_id', $importedIdList)
            ->get();
        foreach ($already_imported as $item) {
            $csvKey = $importedIdMapByCsvKey[$item->imported_id];
            $filteredBuyersCsv->unset($csvKey);
        }

        return $filteredBuyersCsv;
    }

}