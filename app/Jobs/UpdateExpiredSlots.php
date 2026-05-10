<?php

namespace App\Jobs;

use App\Models\TanggalTersedia;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateExpiredSlots implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * Nonaktifkan slot yang tanggalnya sudah lewat.
     */
    public function handle(): void
    {
        $yesterday = now()->subDay()->endOfDay();

        $updated = TanggalTersedia::where('tanggal', '<', $yesterday)
            ->where('is_aktif', true)
            ->update(['is_aktif' => false]);

        if ($updated > 0) {
            Log::info("UpdateExpiredSlots: {$updated} slot dinonaktifkan.");
        }
    }
}
