<?php

namespace App\Console\Commands;

use App\Models\TanggalTersedia;
use Illuminate\Console\Command;

class UpdateExpiredSlotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slots:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nonaktifkan slot yang tanggalnya sudah lewat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update slot yang sudah lewat...');

        $yesterday = now()->subDay()->endOfDay();

        $updated = TanggalTersedia::where('tanggal', '<', $yesterday)
            ->where('is_aktif', true)
            ->update(['is_aktif' => false]);

        if ($updated > 0) {
            $this->info("✓ {$updated} slot berhasil dinonaktifkan.");
        } else {
            $this->info('✓ Tidak ada slot yang perlu dinonaktifkan.');
        }

        return Command::SUCCESS;
    }
}
