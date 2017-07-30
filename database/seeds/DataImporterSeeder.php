<?php

use App\Models\Importer\DataImporter;
use Illuminate\Database\Seeder;

class DataImporterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DataImporter::firstOrNew([
            'name' => DataImporter::MEGA_FAKTURKA_NAME,
        ]);
    }
}
