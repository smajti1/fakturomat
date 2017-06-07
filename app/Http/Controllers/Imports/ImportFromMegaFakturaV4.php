<?php

namespace App\Http\Controllers\Imports;


use App\Helpers\SimpleCsv;
use App\Models\Buyer;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportFromMegaFakturaV4
{

    const IMPORTED_FROM_ID = 'mega_faktura_v4';

    protected $simpleCsv;
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
    }

    public function import()
    {
        $productsCsv = $this->simpleCsv->fileFromRequest('products')->readData();
        $buyersCsv = $this->simpleCsv->new()->fileFromRequest('buyers')->readData();

        DB::transaction(function () use ($productsCsv, $buyersCsv) {
            $this->importProducts($productsCsv);
            $this->importBuyers($buyersCsv);
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
            $tmpProduct->imported_from_id = self::IMPORTED_FROM_ID;
            $tmpProduct->imported_id = $oldId;
            $tmpProduct->user()->associate(Auth::user());
            $tmpProduct->save();
            $products[$oldId] = $tmpProduct;
        }

        return $products;
    }

    private function importBuyers(SimpleCsv $buyersCsv)
    {
        $buyers = [];
        foreach ($buyersCsv->getLines() as $product) {
            $tmpProduct = new Buyer;
            $tmpProduct->name = trim($product[$buyersCsv->getHeaders()['nazwa']]);
            $tmpProduct->city = trim($product[$buyersCsv->getHeaders()['miejscowosc']]);
            $tmpProduct->zip_code = trim($product[$buyersCsv->getHeaders()['kodpoczt']]);
            $tmpProduct->street = trim($product[$buyersCsv->getHeaders()['ulica']]);
            $tmpProduct->tax_id_number = trim($product[$buyersCsv->getHeaders()['nip']]);
            $tmpProduct->regon = trim($product[$buyersCsv->getHeaders()['regon']]);
            $tmpProduct->email = trim($product[$buyersCsv->getHeaders()['email']]);
            $tmpProduct->phone = trim($product[$buyersCsv->getHeaders()['tel']]);

            $oldId = $product[$buyersCsv->getHeaders()['id']];
            $tmpProduct->imported_from_id = self::IMPORTED_FROM_ID;
            $tmpProduct->imported_id = $oldId;
            $tmpProduct->user()->associate(Auth::user());
            $tmpProduct->save();
            $buyers[$oldId] = $tmpProduct;
        }

        return $buyers;
    }

}