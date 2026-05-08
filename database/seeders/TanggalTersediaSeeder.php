<?php

namespace Database\Seeders;

use App\Models\TanggalTersedia;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TanggalTersediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggalData = [
            [
                'tanggal' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'kuota' => 10,
                'keterangan' => 'Slot normal - Hari kerja',
                'is_aktif' => true,
            ],
            [
                'tanggal' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'kuota' => 15,
                'keterangan' => 'Slot weekend - Kapasitas lebih besar',
                'is_aktif' => true,
            ],
            [
                'tanggal' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'kuota' => 8,
                'keterangan' => 'Slot terbatas',
                'is_aktif' => true,
            ],
            [
                'tanggal' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'kuota' => 12,
                'keterangan' => 'Slot normal',
                'is_aktif' => true,
            ],
            [
                'tanggal' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'kuota' => 20,
                'keterangan' => 'Event spesial - Kapasitas maksimal',
                'is_aktif' => true,
            ],
            [
                'tanggal' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'kuota' => 10,
                'keterangan' => 'Slot normal',
                'is_aktif' => true,
            ],
            [
                'tanggal' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'kuota' => 5,
                'keterangan' => 'Libur nasional - Kapasitas terbatas',
                'is_aktif' => false,
            ],
            [
                'tanggal' => Carbon::now()->addDays(21)->format('Y-m-d'),
                'kuota' => 15,
                'keterangan' => 'Slot weekend',
                'is_aktif' => true,
            ],
        ];

        foreach ($tanggalData as $data) {
            TanggalTersedia::create($data);
        }

        $this->command->info('Seeded ' . count($tanggalData) . ' tanggal tersedia');
    }
}
