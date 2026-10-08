<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class mahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table ('mahasiswa') ->insert(array(
            [
            'nim' => '2503024',
            'nama' => 'Muhammad Zaki Mukhlisin',
            'kelas' => 'TI 2A',
            'prodi' => 'D3 Teknik Informatika',
            ]
        ));
    }
}