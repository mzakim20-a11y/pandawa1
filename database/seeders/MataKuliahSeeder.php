<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        DB::table('mata_kuliah')->insert([
            [
                'kodematkul' => '35',
                'namamatkul' => 'Pemrograman Web Lanjut',
                'SKS' => 3,
                'semester' => 4,
            ],
            [
                'kodematkul' => '34',
                'namamatkul' => 'IOT',
                'SKS' => 3,
                'semester' => 5,
            ],
            [
                'kodematkul' => 'BD003',
                'namamatkul' => 'Metode Numerik',
                'SKS' => 4,
                'semester' => 3,
            ],
        ]);
    }
}