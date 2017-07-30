<?php

namespace App\Models\Importer;

use App\Models\Buyer;
use Illuminate\Database\Eloquent\Model;

class DataImportedBuyer extends Model
{
    protected $fillable = [
        'imported_id',
    ];

    public function dataImporter()
    {
        return $this->belongsTo(DataImporter::class);
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}