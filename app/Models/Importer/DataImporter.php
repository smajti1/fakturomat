<?php

namespace App\Models\Importer;

use Illuminate\Database\Eloquent\Model;

class DataImporter extends Model
{

    const MEGA_FAKTURKA_NAME = 'Mega Fakturka v4';

    protected $fillable = [
        'name',
    ];

    public function dataImportedBuyers()
    {
        return $this->hasMany(DataImportedBuyer::class);
    }

    public function dataImportedProducts()
    {
        return $this->hasMany(DataImportedProduct::class);
    }
}