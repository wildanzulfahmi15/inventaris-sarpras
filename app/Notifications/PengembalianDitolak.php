<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengembalianDitolak extends Notification
{
    use Queueable;

    protected $detail;

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $barang = optional($this->detail->barang)->nama_barang ?? 'barang';

        return [
            'title'   => 'Pengembalian Ditolak ❌',
            'message' => 'Pengembalian ' . $barang . ' ditolak',
            'url'     => '/guru/riwayat'
        ];
    }
}