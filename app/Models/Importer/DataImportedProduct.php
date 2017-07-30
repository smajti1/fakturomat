<?php

namespace App\Models\Importer;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class DataImportedProduct extends Model
{
    protected $fillable = [
        'imported_id',
    ];

    public function dataImporter()
    {
        return $this->belongsTo(DataImporter::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}