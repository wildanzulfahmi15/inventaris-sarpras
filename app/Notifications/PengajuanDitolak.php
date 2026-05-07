<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
class PengajuanDitolak extends Notification
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
    $barang = optional($this->detail->barang)->nama_barang;

    return [
        'title'   => 'Pengajuan Ditolak ❌',
        'message' => 'Pengajuan untuk ' . ($barang ?: 'barang') . ' ditolak',
        'url'     => '/guru/riwayat'
    ];
}
}