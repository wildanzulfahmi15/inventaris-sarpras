<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use Carbon\Carbon;

class AutoDeleteBarang extends Command
{
    protected $signature = 'barang:auto-delete';


    protected $description = 'Hapus permanen barang yang sudah lama dihapus';

    public function handle()
    {
        $days = 30;

        $deleted = Barang::onlyTrashed()
            ->where('deleted_at', '<=', Carbon::now()->subDays($days))
            ->forceDelete();

        $this->info("Barang lama berhasil dihapus permanen.");
    }
}
